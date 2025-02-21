<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\garage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MechanicReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Fetch the garage associated with the mechanic
        $garage = garage::where('id', $user->garage_id)->first();

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

        return view('mechanic.reservation.index', compact('appointments'));
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
                ->with('error', 'appointment not found.');
        }

        // Check if the schedule belongs to the authenticated user's garage
        if ($appointment->garage_ref !== $garage->ref) {
            return redirect()->route('mechanic.reservation.index')
                ->with('error', 'You are not authorized to show this appointment.');
        }

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