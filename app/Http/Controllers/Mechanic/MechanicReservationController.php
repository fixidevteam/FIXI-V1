<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\garage;
use App\Models\jour_indisponible;
use App\Notifications\GarageAcceptRdv;
use App\Notifications\GarageCancelledRdv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class MechanicReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     // Get the authenticated user
    //     $user = Auth::user();

    //     // Fetch the garage associated with the mechanic
    //     $garage = garage::where('id', $user->garage_id)->first();

    //     // Check if the garage exists
    //     if (!$garage) {
    //         return redirect()->back()->with('error', 'Garage not found.');
    //     }

    //     // Fetch appointments for the garage
    //     $appointments = Appointment::where('garage_ref', $garage->ref)
    //         ->get(['id', 'user_full_name', 'appointment_day', 'appointment_time', 'status'])
    //         ->map(function ($appointment) {
    //             $color = match ($appointment->status) {
    //                 'en_cour' => 'orange',
    //                 'confirmed' => 'green',
    //                 'cancelled' => 'red',
    //                 default => 'blue'
    //             };

    //             return [
    //                 'title' => 'Reservation: ' . $appointment->user_full_name,
    //                 'start' => $appointment->appointment_day . 'T' . $appointment->appointment_time,
    //                 'url' => route('mechanic.reservation.show', $appointment->id),
    //                 'time' => $appointment->appointment_time,
    //                 'color' => $color
    //             ];
    //         })
    //         ->toArray();

    //     return view('mechanic.reservation.index', compact('appointments'));
    // }
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Fetch the garage associated with the mechanic
        $garage = Garage::where('id', $user->garage_id)->first();

        // Check if the garage exists
        if (!$garage) {
            return redirect()->back()->with('error', 'Garage not found.');
        }

        // Fetch appointments for the garage
        $appointments = Appointment::where('garage_ref', $garage->ref)
            ->get(['id', 'user_full_name', 'appointment_day', 'appointment_time', 'status'])
            ->map(function ($appointment) {
                $color = match ($appointment->status) {
                    'en_cour' => 'orange',
                    'confirmed' => 'green',
                    'cancelled' => 'red',
                    default => 'blue'
                };

                return [
                    'title' => 'Reservation: ' . $appointment->user_full_name,
                    'start' => $appointment->appointment_day . 'T' . $appointment->appointment_time,
                    'url' => route('mechanic.reservation.show', $appointment->id),
                    'time' => $appointment->appointment_time,
                    'color' => $color
                ];
            })
            ->toArray();

        // Fetch unavailable days from jour_indisponibles table
        $unavailableDays = jour_indisponible::where('garage_ref', $garage->ref)
            ->pluck('date'); // Assuming 'day' is stored in 'YYYY-MM-DD' format

        // Add unavailable days as gray events
        foreach ($unavailableDays as $day) {
            $appointments[] = [
                'title' => 'Indisponible',
                'start' => $day,
                'color' => 'gray',
                'display' => 'background' // Make it appear as a background event
            ];
        }

        return view('mechanic.reservation.index', compact('appointments'));
    }

    /**
     * index as list of appointments.
     */
    public function list()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Fetch the garage associated with the mechanic
        $garage = Garage::where('id', $user->garage_id)->first();

        // Check if the garage exists
        if (!$garage) {
            return redirect()->back()->with('error', 'Garage not found.');
        }

        // Fetch paginated appointments for the garage, ordered by the latest
        $appointments = Appointment::where('garage_ref', $garage->ref)
            ->latest() // Orders by `created_at` descending
            ->paginate(10); // Adjust per page as needed

        return view('mechanic.reservation.list', compact('appointments'));
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
        // Get the authenticated user
        $user = Auth::user();

        // Fetch the garage associated with the mechanic
        $garage = garage::where('id', $user->garage_id)->first();

        $appointment = Appointment::find($id);

        // Check if the schedule exists
        if (!$appointment) {
            return redirect()->route('mechanic.reservation.index')
                ->with('error', 'Rendez-vous introuvable.');
        }

        // Check if the schedule belongs to the authenticated user's garage
        if ($appointment->garage_ref !== $garage->ref) {
            return redirect()->route('mechanic.reservation.index')
                ->with('error', 'Vous n\'êtes pas autorisé à afficher ce rendez-vous.');
        }
        // Store the referring URL before navigating to show page
        session(['previous_url' => url()->previous()]);

        return view('mechanic.reservation.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
    /**
     * Update the status of appointment.
     */
    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = $request->status;
        $appointment->save();
        if ($appointment->status === 'cancelled') {
            Notification::route('mail', $appointment->user_email)
                ->notify(new GarageCancelledRdv($appointment, 'la réservation a été annulée par le garage'));
        } elseif ($appointment->status === 'Confirmed') {
            Notification::route('mail', $appointment->user_email)
                ->notify(new GarageAcceptRdv($appointment, 'la réservation a été confirmée par le garage'));
        }

        // Retrieve the previous URL from the session
        $previousUrl = session('previous_url');

        if (str_contains($previousUrl, route('mechanic.reservation.index'))) {
            return redirect()->route('mechanic.reservation.index')->with('success', 'Le statut a été mis à jour avec succès.');
        } else {
            return redirect()->route('mechanic.reservation.list')->with('success', 'Le statut a été mis à jour avec succès.');
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}