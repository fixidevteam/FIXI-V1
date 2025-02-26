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
use Illuminate\Support\Facades\Notification;

class ConfirmationRdv extends Controller
{
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

        // Fetch paginated appointments for the garage, ordered by the latest
        $appointments = Appointment::where('garage_ref', $garage->ref)->where('status', 'en_cour')
            ->latest() // Orders by `created_at` descending
            ->paginate(10); // Adjust per page as needed

        return view('mechanic.reservation.confirmationList', compact('appointments'));
    }
    public function  accepter($id)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Fetch the garage associated with the mechanic
        $garage = garage::where('id', $user->garage_id)->first();

        $appointment = Appointment::where('id', $id)->where('garage_ref', $user->garage->ref)->first();
        $appointment->update(['status' => 'confirmed']);
        Notification::route('mail', $appointment->user_email)
            ->notify(new GarageAcceptRdv($appointment, 'Une réservation a été confirmée par le garage'));

        session()->flash('success', 'Rendez-vous');
        session()->flash('subtitle', 'le status de rendez-vous a été modifié avec succès .');
        return back();
    }
    public function  annuler($id)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Fetch the garage associated with the mechanic
        $garage = garage::where('id', $user->garage_id)->first();

        $appointment = Appointment::where('id', $id)->where('garage_ref', $user->garage->ref)->first();
        $appointment->update(['status' => 'cancelled']);
        Notification::route('mail', $appointment->user_email)
            ->notify(new GarageCancelledRdv($appointment, 'Une réservation a été annulée par le garage'));

        session()->flash('success', 'Rendez-vous');
        session()->flash('subtitle', 'le status de rendez-vous a été modifié avec succès .');
        return back();
    }
}
