<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\garage;
use App\Models\jour_indisponible;
use App\Notifications\GarageAcceptRdv;
use App\Notifications\GarageCancelledRdv;
use App\Notifications\GarageUpdateRdv;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class MechanicReservationController extends Controller
{
    // Define the constant for repeated messages
    const GARAGE_NOT_FOUND = 'Garage introuvable';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Fetch the garage associated with the mechanic
        $garage = Garage::where('id', $user->garage_id)->first();

        // Check if the garage exists
        if (!$garage) {
            return redirect()->back()->with('error', self::GARAGE_NOT_FOUND);
        }

        // Fetch appointments for the garage
        $appointments = Appointment::where('garage_ref', $garage->ref)
            ->get(['id', 'user_full_name', 'appointment_day', 'appointment_time', 'status'])
            ->map(function ($appointment) {
                $color = match ($appointment->status) {
                    'en cours' => 'orange',
                    'confirmé' => 'green',
                    'annulé' => 'red',
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
    // public function list()
    // {
    //     // Get the authenticated user
    //     $user = Auth::user();

    //     // Fetch the garage associated with the mechanic
    //     $garage = garage::where('id', $user->garage_id)->first();

    //     // Check if the garage exists
    //     if (!$garage) {
    //         return redirect()->back()->with('error', self::GARAGE_NOT_FOUND);
    //     }

    //     // Fetch paginated appointments for the garage, ordered by the latest
    //     $appointments = Appointment::where('garage_ref', $garage->ref)
    //         ->latest() // Orders by `created_at` descending
    //         ->paginate(10); // Adjust per page as needed

    //     return view('mechanic.reservation.list', compact('appointments'));
    // }

    public function list(Request $request)
    {
        $user = Auth::user();
        $garage = garage::where('id', $user->garage_id)->first();

        if (!$garage) {
            return redirect()->back()->with('error', self::GARAGE_NOT_FOUND);
        }

        $query = Appointment::where('garage_ref', $garage->ref);

        // Filter for past appointments to close
        if ($request->has('filter') && $request->filter == 'to_close') {
            $now = Carbon::now();
            $query->where(function ($q) use ($now) {
                $q->where('appointment_day', '<', $now->format('Y-m-d'))
                    ->orWhere(function ($q2) use ($now) {
                        $q2->where('appointment_day', $now->format('Y-m-d'))
                            ->where('appointment_time', '<', $now->format('H:i:s'));
                    });
            })
                ->whereIn('status', ['en cours']);
        } else {
            // Default: show active appointments
            $now = Carbon::now();
            $query->where(function ($q) use ($now) {
                $q->where('appointment_day', '>', $now->format('Y-m-d'))
                    ->orWhere(function ($q2) use ($now) {
                        $q2->where('appointment_day', $now->format('Y-m-d'))
                            ->where('appointment_time', '>=', $now->format('H:i:s'));
                    });
            })
                ->whereIn('status', ['en cours', 'confirmé', 'annulé']);
        }

        $appointments = $query->latest()->paginate(10);

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

        $user = Auth::user();
        // Fetch the garage associated with the mechanic
        $garage = Garage::where('id', $user->garage_id)->first();
        $appointment = Appointment::where('id', $id)->where('garage_ref', $garage->ref)->first();
        if ($appointment) {
            return view('mechanic.reservation.edit', compact('appointment', 'garage'));
        } else {
            return back();
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
        ]);

        $user = Auth::user();
        // Fetch the garage associated with the mechanic
        $garage = Garage::where('id', $user->garage_id)->first();
        $appointment = Appointment::where('id', $id)->where('garage_ref', $garage->ref)->first();
        if ($appointment) {
            $data['status'] = 'en cours';
            $appointment->update($data);
            Notification::route('mail',$appointment->user_email)->notify(
                new GarageUpdateRdv($appointment, 'Une réservation a été modifée par le garage.')
            );
            // end sending email
            session()->flash('success', 'Rendez-vous');
            session()->flash('subtitle', 'Votre rendez-vous a été modifié avec succès.');
            return redirect()->route('mechanic.reservation.show',$appointment);
        }
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
    /**
     * Close a past appointment
     */
    public function close(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $now = Carbon::now();
        $appointmentDateTime = Carbon::parse($appointment->appointment_day . ' ' . $appointment->appointment_time);

        // Verify the appointment is in the past
        if ($appointmentDateTime->gt($now)) {
            return redirect()->back()->with('error', 'Vous ne pouvez clôturer que les rendez-vous passés.');
        }

        // Validate the request
        $request->validate([
            'presence' => 'required|in:present,absent'
        ]);

        // Update appointment
        $appointment->status = 'clôturé';
        $appointment->presence_status = $request->presence == 'present' ? 'present' : 'absent';
        $appointment->closed_at = $now;
        $appointment->save();

        return redirect()->route('mechanic.reservation.list', ['filter' => 'to_close'])
            ->with('success', 'Le rendez-vous a été clôturé avec le statut: ' .
                ($request->presence == 'present' ? '✅ Présent' : '❌ Absent'));
    }
    public function cloturer(Request $request)
    {
        $user = Auth::user();
        $garage = garage::where('id', $user->garage_id)->first();

        if (!$garage) {
            return redirect()->back()->with('error', self::GARAGE_NOT_FOUND);
        }

        $appointments = Appointment::where('garage_ref', $garage->ref)
            ->where('status', 'clôturé')
            ->latest()
            ->paginate(10);

        return view('mechanic.reservation.cloturer', compact('appointments'));
    }
}