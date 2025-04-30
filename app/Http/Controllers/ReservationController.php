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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

class ReservationController extends Controller
{
    // Define the constant for repeated messages
    const APPOINTMENT_NOT_FOUND = 'Rendez-vous introuvable.';
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
            return back()->with('error', self::APPOINTMENT_NOT_FOUND);
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
            return back()->with('error', self::APPOINTMENT_NOT_FOUND);
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
            $data['status'] = 'en cours';
            $appointment->update($data);
            // Send email notifications
            $this->sendUpdateNotifications($appointment);
            // end sending email
            session()->flash('success', 'Rendez-vous');
            session()->flash('subtitle', 'Votre rendez-vous a été modifié avec succès.');

            return redirect()->route('RDV.show', $appointment);
        } else {
            return back()->with('error', self::APPOINTMENT_NOT_FOUND);
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
            $appointment->update(['status' => 'annulé']);


            // Send email notifications
            $this->sendCancellationNotifications($appointment);

            // Send SMS to customer
            $this->sendAppointmentCancellationSms($appointment);


            // end sending email
            session()->flash('success', 'Rendez-vous');
            session()->flash('subtitle', 'Votre rendez-vous a été annulé avec succès.');

            return redirect()->route('RDV.show', $appointment);
        } else {
            return back()->with('error', self::APPOINTMENT_NOT_FOUND);
        }
    }
    protected function sendUpdateNotifications(Appointment $appointment)
    {
        // Send to admins
        $admins = Admin::all();
        foreach ($admins as $admin) {
            Notification::route('mail', $admin->email)
                ->notify(new UpdatedRdv($appointment, 'Une réservation a été modifée par le client.', true, $admin));
        }

        // Send to mechanics
        $garage = Garage::where('ref', $appointment->garage_ref)->first();
        if ($garage) {
            $mechanics = $garage->mechanics;
            foreach ($mechanics as $mechanic) {
                Notification::route('mail', $mechanic->email)
                    ->notify(new UpdatedRdv($appointment, 'Une réservation a été modifée par le client.', false, null));
            }
        }
    }

    protected function sendCancellationNotifications(Appointment $appointment)
    {
        // Send to admins
        $admins = Admin::all();
        foreach ($admins as $admin) {
            Notification::route('mail', $admin->email)
                ->notify(new CancelledRdv($appointment, 'Une réservation a été annulée par le client.', true, $admin));
        }

        // Send to mechanics
        $garage = Garage::where('ref', $appointment->garage_ref)->first();
        if ($garage) {
            $mechanics = $garage->mechanics;
            foreach ($mechanics as $mechanic) {
                Notification::route('mail', $mechanic->email)
                    ->notify(new CancelledRdv($appointment, 'Une réservation a été annulée par le client.', false, null));
            }
        }
    }


    protected function sendAppointmentCancellationSms(Appointment $appointment)
    {
        $garage = Garage::where('ref', $appointment->garage_ref)->first();

        $smsPayload = [
            "defaultRegionCode" => "MA",
            "messages" => [
                [
                    "to" => [
                        [
                            "messageId" => "appt-cancel-" . time(),
                            "phone" => $garage->telephone
                        ]
                    ],
                    "content" => "Bonjour " . $garage->name . ",\n" .
                        "Le client " . $appointment->user_full_name . " a annulé son RDV prévu le " .
                        Carbon::parse($appointment->appointment_day)->format('d/m/Y') . " à " .
                        Carbon::parse($appointment->appointment_time)->format('H:i') . ".\n" .
                        "FIXI.MA",
                    "transliterateMessage" => false,
                    "messageEncoding" => 0
                ]
            ]
        ];

        $this->sendSms($smsPayload);
    }

    protected function sendSms(array $payload)
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'x-shortlink-apikey' => config('services.shortlink.api_key'),
                'x-shortlink-apitoken' => config('services.shortlink.api_token'),
            ])->post('https://app.shortlink.pro/api/v1/sms/send/', $payload);


            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}