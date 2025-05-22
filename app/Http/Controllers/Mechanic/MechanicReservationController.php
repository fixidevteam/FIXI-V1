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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class MechanicReservationController extends Controller
{
    // Define the constant for repeated messages
    const GARAGE_NOT_FOUND = 'Garage introuvable';

    /**
     * Send SMS using MoroccoSMS API
     */
    protected function sendSMS($phone, $message)
    {
        // Format phone number (remove leading 0, add 212 country code)
        $phone = preg_replace('/^0/', '212', $phone);

        // Prepare MoroccoSMS API parameters
        $params = [
            'sub_account' => config('services.moroccosms.api_key'),
            'sub_account_pass' => config('services.moroccosms.api_token'),
            'action' => 'send_sms',
            'sender_id' => config('services.moroccosms.api_name'),
            'message' => $message,
            'recipients' => $phone
        ];

        // Build the query string
        $queryString = http_build_query($params);

        // Send SMS via MoroccoSMS API
        $response = Http::get('https://www.moroccosms.com/sms/api_v1?' . $queryString);

        // Log the response for debugging
        Log::info('MoroccoSMS API Response:', [
            'phone' => $phone,
            'message' => $message,
            'response' => $response->body()
        ]);
    }

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

    // public function list(Request $request)
    // {
    //     $user = Auth::user();
    //     $garage = garage::where('id', $user->garage_id)->first();

    //     if (!$garage) {
    //         return redirect()->back()->with('error', self::GARAGE_NOT_FOUND);
    //     }

    //     $query = Appointment::where('garage_ref', $garage->ref);

    //     $filter = $request->filter ?? 'active'; // Get the filter or default to 'active'

    //     // Filter for past appointments to close
    //     if ($filter == 'to_close') {
    //         $now = Carbon::now();
    //         $query->where(function ($q) use ($now) {
    //             $q->where('appointment_day', '<', $now->format('Y-m-d'))
    //                 ->orWhere(function ($q2) use ($now) {
    //                     $q2->where('appointment_day', $now->format('Y-m-d'))
    //                         ->where('appointment_time', '<', $now->format('H:i:s'));
    //                 });
    //         })
    //             ->whereIn('status', ['en cours']);
    //     } else {
    //         // Default: show active appointments
    //         $now = Carbon::now();
    //         $query->where(function ($q) use ($now) {
    //             $q->where('appointment_day', '>', $now->format('Y-m-d'))
    //                 ->orWhere(function ($q2) use ($now) {
    //                     $q2->where('appointment_day', $now->format('Y-m-d'))
    //                         ->where('appointment_time', '>=', $now->format('H:i:s'));
    //                 });
    //         })
    //             ->whereIn('status', ['en cours', 'confirmé', 'annulé']);
    //     }

    //     $appointments = $query->latest()->paginate(10);

    //     // Pass the filter to the view
    //     return view('mechanic.reservation.list', compact('appointments', 'filter'));
    // }
    public function list(Request $request)
    {
        $user = Auth::user();
        $garage = garage::where('id', $user->garage_id)->first();

        if (!$garage) {
            return redirect()->back()->with('error', self::GARAGE_NOT_FOUND);
        }

        $query = Appointment::where('garage_ref', $garage->ref);

        // Get filter values from request
        $date = $request->date;
        $status = $request->status;

        // Apply date filter if provided (only when user specifically requests a date)
        if ($date) {
            $query->where('appointment_day', $date);
        }
        // Removed the else block to show all dates by default

        // Apply status filter if provided
        if ($status) {
            $query->where('status', $status);
        } else {
            // Default status filtering when no status is specified
            $query->whereIn('status', ['en cours', 'confirmé', 'annulé']);
        }

        $appointments = $query->latest()->paginate(10);

        // Append all query parameters to pagination links
        $appointments->appends($request->query());

        // Pass the filters to the view
        return view('mechanic.reservation.list', [
            'appointments' => $appointments,
            'date' => $date,
            'status' => $status
        ]);
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

            // Send notification email
            Notification::route('mail', $appointment->user_email)->notify(
                new GarageUpdateRdv($appointment, 'Une réservation a été modifée par le garage.')
            );

            // Send SMS using MoroccoSMS
            $message = "Bonjour " . $appointment->user_full_name . "\nVotre RDV a été modifié par le garage.\nRDV à " .
                \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') . " le " .
                \Carbon\Carbon::parse($appointment->appointment_day)->format('d/m/Y') . "\nChez " .
                $garage->name . "\nFIXI.MA";
            $this->sendSMS($appointment->user_phone, $message);

            session()->flash('success', 'Rendez-vous');
            session()->flash('subtitle', 'Votre rendez-vous a été modifié avec succès.');
            return redirect()->route('mechanic.reservation.show', $appointment);
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
        $garage = Auth::user()->garage;

        if ($appointment->status === 'annulé') {
            // Send cancellation email
            Notification::route('mail', $appointment->user_email)
                ->notify(new GarageCancelledRdv($appointment, 'la réservation a été annulée par le garage'));

            // Send cancellation SMS
            $message = "Bonjour " . $appointment->user_full_name . "\nVotre RDV du " .
                \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') . " le " .
                \Carbon\Carbon::parse($appointment->appointment_day)->format('d/m/Y') .
                "\nChez " . $garage->name . " a été annulé.\nPour plus d'information contacter le garage " .
                $garage->telephone . "\nFIXI.MA";
            $this->sendSMS($appointment->user_phone, $message);
        } elseif ($appointment->status === 'confirmé') {
            // Send confirmation email
            Notification::route('mail', $appointment->user_email)
                ->notify(new GarageAcceptRdv($appointment, 'la réservation a été confirmée par le garage'));

            // Send confirmation SMS
            $message = "Bonjour " . $appointment->user_full_name . "\nVotre RDV a été confirmé.\nRDV à " .
                \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') . " le " .
                \Carbon\Carbon::parse($appointment->appointment_day)->format('d/m/Y') .
                "\nChez " . $garage->name . "\nFIXI.MA";
            $this->sendSMS($appointment->user_phone, $message);

            session()->flash('success', 'Rendez-vous');
            session()->flash('subtitle', 'le status de rendez-vous a été modifié avec succès.');
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
     * Close a past appointment
     */
    public function close(Request $request, $id)
    {

        $appointment = Appointment::findOrFail($id);
        $now = Carbon::now();
        $appointmentDateTime = Carbon::parse($appointment->appointment_day . ' ' . $appointment->appointment_time);

        // // Verify the appointment is in the past
        // if ($appointmentDateTime->gt($now)) {
        //     return redirect()->back()->with('error', 'Vous ne pouvez clôturer que les rendez-vous passés.');
        // }

        // Validate the request
        $request->validate([
            'presence' => 'required|in:present,absent'
        ]);

        // Update appointment
        $appointment->status = 'clôturé';
        $appointment->presence_status = $request->presence == 'present' ? 'present' : 'absent';
        $appointment->closed_at = $now;
        $appointment->save();


        if ($request->convertir === 'oui') {
            return redirect()->route("mechanic.reservation.convertForm", $appointment);
        } else {
            return redirect()->route('mechanic.reservation.list', ['filter' => 'to_close'])
                ->with('success', 'Le rendez-vous a été clôturé avec le statut: ' .
                    ($request->presence == 'present' ? '✅ Présent' : '❌ Absent'));
        }
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