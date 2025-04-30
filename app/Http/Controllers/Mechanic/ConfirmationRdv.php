<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\garage;
use App\Notifications\CancelledRdv;
use App\Notifications\GarageAcceptRdv;
use App\Notifications\GarageCancelledRdv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

class ConfirmationRdv extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Fetch the garage associated with the mechanic
        $garage = Garage::where('id', $user->garage_id)->first();

        // Check if the garage exists
        if (!$garage) {
            return redirect()->back()->with('error', 'Garage introuvable.');
        }

        $searchDate = $request->input('search');

        // Fetch appointments filtered by selected date
        $query = Appointment::where('garage_ref', $garage->ref)
            ->where('status', 'en cours')
            ->whereDate('appointment_day', '>=', now()->toDateString())
            ->orderBy('updated_at', 'desc'); // Orders by `created_at` descending

        if ($searchDate) {
            $query->whereDate('appointment_day', $searchDate);
        }

        $appointments = $query->paginate(10); // Adjust per page as needed

        return view('mechanic.reservation.confirmationList', compact('appointments', 'searchDate'));
    }
    public function  accepter($id)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Fetch the garage associated with the mechanic
        $garage = garage::where('id', $user->garage_id)->first();

        $appointment = Appointment::where('id', $id)->where('garage_ref', $user->garage->ref)->first();
        $appointment->update(['status' => 'confirmé']);
        Notification::route('mail', $appointment->user_email)
            ->notify(new GarageAcceptRdv($appointment, 'Une réservation a été confirmée par le garage.'));

        // Prepare SMS payload
        $smsPayload = [
            "defaultRegionCode" => "MA",
            "messages" => [
                [
                    // "from" => "SHORTLINK",
                    "to" => [
                        [
                            "messageId" => "appt-verif-" . time(),
                            "phone" => $appointment->user_phone
                        ]
                    ],
                    "content" => "Bonjour " . $appointment->user_full_name . "\nVotre RDV a été confirmé.\nRDV à " . \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') . " le " . \Carbon\Carbon::parse($appointment->appointment_day)->format('d/m/Y') . "\nChez " . $garage->name . "\nFIXI.MA",
                    "transliterateMessage" => false,
                    "messageEncoding" => 0
                ]
            ]
        ];

        // Send SMS via Shortlink API
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'x-shortlink-apikey' => config('services.shortlink.api_key'),
            'x-shortlink-apitoken' => config('services.shortlink.api_token'),
        ])->post('https://app.shortlink.pro/api/v1/sms/send/', $smsPayload);

        session()->flash('success', 'Rendez-vous');
        session()->flash('subtitle', 'le status de rendez-vous a été modifié avec succès.');
        return back();
    }
    public function  annuler($id)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Fetch the garage associated with the mechanic
        $garage = garage::where('id', $user->garage_id)->first();

        $appointment = Appointment::where('id', $id)->where('garage_ref', $user->garage->ref)->first();
        $appointment->update(['status' => 'annulé']);
        Notification::route('mail', $appointment->user_email)
            ->notify(new GarageCancelledRdv($appointment, 'Une réservation a été annulée par le garage.'));

        // Prepare SMS payload
        $smsPayload = [
            "defaultRegionCode" => "MA",
            "messages" => [
                [
                    // "from" => "SHORTLINK",
                    "to" => [
                        [
                            "messageId" => "appt-verif-" . time(),
                            "phone" => $appointment->user_phone
                        ]
                    ],
                    "content" => "Bonjour " . $appointment->user_full_name . "\nVotre RDV du " . \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') . " le " . \Carbon\Carbon::parse($appointment->appointment_day)->format('d/m/Y') . "\nChez " . $garage->name . "a été annulé.\nPour plus d’information contacter le garage ".$garage->telephone."\nFIXI.MA",
                    "transliterateMessage" => false,
                    "messageEncoding" => 0
                ]
            ]
        ];

        // Send SMS via Shortlink API
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'x-shortlink-apikey' => config('services.shortlink.api_key'),
            'x-shortlink-apitoken' => config('services.shortlink.api_token'),
        ])->post('https://app.shortlink.pro/api/v1/sms/send/', $smsPayload);

        session()->flash('success', 'Rendez-vous');
        session()->flash('subtitle', 'le status de rendez-vous a été modifié avec succès.');
        return back();
    }
}
