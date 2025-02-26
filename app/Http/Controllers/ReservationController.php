<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Appointment;
use App\Models\garage;
use App\Models\Mechanic;
use App\Models\nom_categorie;
use App\Notifications\CancelledRdv;
use App\Notifications\UpdatedRdv;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ReservationController extends Controller
{

    /**
     * 
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth()->user()->email;

        // Fetch paginated appointments ordered by latest
        $appointments = Appointment::where('user_email', $user)
            ->join('garages', 'appointments.garage_ref', '=', 'garages.ref')
            ->select('appointments.*', 'garages.name as garage_name')
            ->orderBy('appointments.created_at', 'desc') // Order by latest
            ->paginate(10); // Paginate with 10 records per page

        // Check if appointments exist
        if ($appointments->isEmpty()) {
            return back()->with('error', 'Vous n\'avez aucun rendez-vous.');
        }

        // Pass the data to the view
        return view('userRdv.index', compact('appointments'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userEmail = Auth()->user()->email;

        // Find the appointment by id and user email
        $appointment = Appointment::where('id', $id)
            ->where('user_email', $userEmail)
            ->first();

        if (!$appointment) {
            return back()->with('error', 'Rendez-vous introuvable.');
        }

        $garage = garage::where('ref', $appointment->garage_ref)->first();

        return view('userRdv.show', compact('appointment', 'garage'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $appointment = Appointment::where('id', $id)->where('user_email', Auth::user()->email)->first();
        if ($appointment) {
            $garage = garage::where('ref', $appointment->garage_ref)->first();
            return view('userRdv.edit', compact('appointment', 'garage'));
        } else {
            return back()->with('error', 'Rendez-vous introuvable.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'appointment_day' => 'required|date',
            'appointment_time' => 'required',
            'categorie_de_service' => 'required|string',
            'modele' => 'nullable|string',
            'objet_du_RDV' => 'nullable|string'
        ]);
        $appointment = Appointment::where('id', $id)->where('user_email', Auth::user()->email)->first();
        if ($appointment) {
            $data['status'] = 'en_cour';
            $appointment->update($data);
            // send email to garage 
            $admins = Admin::all();
            foreach ($admins as $admin) {
                Notification::route('mail', $admin->email)
                    ->notify(new UpdatedRdv($appointment, 'Une réservation a été modifée par le client.', true, $admin));
            }
            // Fetch garage by ref
            $garage = Garage::where('ref', $appointment->garage_ref)->first();
            if ($garage) {
                // Get mechanics related to the garage
                $mechanics = $garage->mechanics;
                if ($mechanics->count() > 0) {
                    // Notify all mechanics associated with the garage
                    foreach ($mechanics as $mechanic) {
                        Notification::route('mail', $mechanic->email)
                    ->notify(new UpdatedRdv($appointment, 'Une réservation a été modifée par le client.', false, null));
                    }
                }
            }
            // end sending email
            session()->flash('success', 'Rendez-vous');
            session()->flash('subtitle', 'Votre rendez-vous a été modifié avec succès.');

            return redirect()->route('RDV.show', $appointment);
        } else {
            return back()->with('error', 'Rendez-vous introuvable.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $appointment = Appointment::where('id', $id)->where('user_email', Auth::user()->email)->first();
        if ($appointment) {
            if (Carbon::parse($appointment->appointment_day)->diffInHours(now()) < 24) {
                session()->flash('error', 'Rendez-vous');
                session()->flash('subtitle', "L’annulation est possible uniquement si le RDV est à plus de 24h.");
                return redirect()->route('RDV.show', $appointment);
            }
            $appointment->update(['status' => 'cancelled']);
            // send email to garage 
            // send to admin : 
            $admins = Admin::all();
            foreach ($admins as $admin) {
                Notification::route('mail', $admin->email)
                    ->notify(new CancelledRdv($appointment, 'Une réservation a été annulée par le client.', true, $admin));
            }
            // Fetch garage by ref
            $garage = Garage::where('ref', $appointment->garage_ref)->first();
            if ($garage) {
                // Get mechanics related to the garage
                $mechanics = $garage->mechanics;
                if ($mechanics->count() > 0) {
                    // Notify all mechanics associated with the garage
                    foreach ($mechanics as $mechanic) {
                        Notification::route('mail', $mechanic->email)
                            ->notify(new CancelledRdv($appointment, 'Une réservation a été annulée par le client.', false, null));
                    }
                }
            } else {
                session()->flash('error', 'Garage introuvable.');
                return redirect()->route('RDV.show', $appointment);
            }


            // end sending email
            session()->flash('success', 'Rendez-vous');
            session()->flash('subtitle', 'Votre rendez-vous a été annulé avec succès.');

            return redirect()->route('RDV.show', $appointment);
        } else {
            return back()->with('error', 'Rendez-vous introuvable.');
        }
    }
}
