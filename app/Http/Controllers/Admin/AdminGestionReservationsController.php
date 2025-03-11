<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\garage;
use App\Notifications\GarageAcceptRdv;
use App\Notifications\GarageCancelledRdv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AdminGestionReservationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get all garages for the filter dropdown
        $garages = Garage::all();

        // Build the appointment query
        $query = Appointment::query();

        if ($request->has('garage_ref') && !empty($request->garage_ref)) {
            $query->where('garage_ref', $request->garage_ref);
        }

        // Fetch appointments (order as needed)
        $appointments = $query->orderBy('created_at', 'desc')
            ->get(['id', 'user_full_name', 'appointment_day', 'appointment_time', 'status']);

        // Map each appointment to FullCalendar event format
        $events = $appointments->map(function ($appointment) {
            $color = match ($appointment->status) {
                'en cours'   => 'orange',
                'confirmé' => 'green',
                'annulé' => 'red',
                default     => 'blue',
            };

            return [
                'title' => 'Reservation: ' . $appointment->user_full_name,
                'start' => $appointment->appointment_day . 'T' . $appointment->appointment_time,
                'url'   => route('admin.gestionReservations.show', $appointment->id),
                'time'  => $appointment->appointment_time,
                'color' => $color,
            ];
        })->toArray();

        return view('admin.gestionReservations.index', compact('garages', 'events'));
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
        $appointment = Appointment::find($id);

        if (empty($appointment)) {
            return back();
        }
        return view('admin.gestionReservations.show', compact('appointment'));
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
        if ($appointment->status === 'annulé') {
            Notification::route('mail', $appointment->user_email)
                ->notify(new GarageCancelledRdv($appointment, 'la réservation a été annulée par le garage'));
        } elseif ($appointment->status === 'confirmé') {
            Notification::route('mail', $appointment->user_email)
                ->notify(new GarageAcceptRdv($appointment, 'la réservation a été confirmée par le garage'));
        }
        return redirect()->back()->with('success', 'Le statut a été mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
