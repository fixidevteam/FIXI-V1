<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\GarageSchedule;
use App\Models\GarageUnavailableTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    public function getAvailableDates(Request $request)
    {
        $garage_ref = $request->query('garage_ref');

        // Fetch all schedules for this garage
        $schedules = GarageSchedule::where('garage_ref', $garage_ref)->get();

        $availability = [];
        foreach ($schedules as $schedule) {
            $dayOfWeek = $schedule->available_day;
            $availability[$dayOfWeek] = true;
        }

        // Generate available dates for the next 365 days
        $dates = [];
        $today = Carbon::today();
        for ($i = 0; $i < 365; $i++) {
            $date = $today->copy()->addDays($i);
            $dayOfWeek = $date->dayOfWeek;

            if (isset($availability[$dayOfWeek])) {
                $dates[] = $date->format('Y-m-d');
            }
        }

        return response()->json([
            'available_dates' => $dates,
        ]);
    }

    public function getTimeSlots(Request $request)
    {
        $garage_ref = $request->query('garage_ref');
        $selectedDate = $request->query('date');

        // Get the day of the week for the selected date
        $dayOfWeek = Carbon::parse($selectedDate)->dayOfWeek;

        // Fetch the garage's schedule for the selected day
        $schedule = GarageSchedule::where('garage_ref', $garage_ref)
            ->where('available_day', $dayOfWeek)
            ->first();

        if (!$schedule) {
            return response()->json([
                'time_slots' => [],
            ]);
        }

        // Fetch unavailable periods for the selected date
        $unavailableTimes = GarageUnavailableTime::where('garage_ref', $garage_ref)
            ->where('unavailable_day', $dayOfWeek)
            ->get();

        // Generate available time slots (every 30 minutes)
        $fromTime = Carbon::createFromFormat('H:i:s', $schedule->available_from);
        $toTime = Carbon::createFromFormat('H:i:s', $schedule->available_to);
        $slots = [];

        while ($fromTime->lessThanOrEqualTo($toTime)) {
            $timeSlot = $fromTime->format('H:i:s');

            // Check if the slot falls within an unavailable period
            $isUnavailable = $unavailableTimes->contains(function ($unavailable) use ($timeSlot) {
                return $timeSlot >= $unavailable->unavailable_from && $timeSlot < $unavailable->unavailable_to;
            });

            // Check if the slot is already booked
            $isBooked = Appointment::where('garage_ref', $garage_ref)
                ->where('appointment_day', $selectedDate)
                ->where('appointment_time', $timeSlot)
                ->exists();

            if (!$isUnavailable && !$isBooked) {
                $slots[] = $timeSlot;
            }

            $fromTime->addHour(); // Generate 30-minute time slots
        }

        return response()->json([
            'time_slots' => $slots,
        ]);
    }

    public function bookAppointment(Request $request)
    {
        // Validate the request
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'garage_ref' => 'required|string|max:255',
            'categorie_de_service' => 'required|string|max:255',
            'appointment_day' => 'required|date',
            'appointment_time' => 'required|date_format:H:i:s',
            'categorie_de_service' => 'required|string|max:255',
            'modele' => 'nullable|string|max:255',
            'numero_immatriculation' => 'nullable|string|max:255',
            'objet_du_RDV' => 'nullable|string|max:255',
        ]);

        // Check if the selected slot is still available
        $isAvailable = GarageSchedule::where('garage_ref', $request->garage_ref)
            ->where('available_day', Carbon::parse($request->appointment_day)->dayOfWeek)
            ->where('available_from', '<=', $request->appointment_time)
            ->where('available_to', '>=', $request->appointment_time)
            ->exists();

        if (!$isAvailable) {
            return response()->json(['message' => 'The selected time slot is no longer available.'], 400);
        }

        // Generate a verification code
        $verificationCode = Str::random(6); // 6-digit code
        $email = $request->email;

        // Store the verification code in cache with an expiration time (e.g., 10 minutes)
        Cache::put('verification_code_' . $email, $verificationCode, now()->addMinutes(10));

        // Send the verification code to the user's email
        Mail::raw("Your verification code is: $verificationCode", function ($message) use ($email) {
            $message->to($email)->subject('Appointment Verification Code');
        });

        return response()->json([
            'message' => 'Verification code sent to your email. Please enter the code to confirm your appointment.',
            'status' => 'verification_required',
        ]);
    }

    public function verifyAppointment(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'nullable|email|max:255',
            'verification_code' => 'required|string|max:6',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'garage_ref' => 'required|string|max:255',
            'appointment_day' => 'required|date',
            'appointment_time' => 'required|date_format:H:i:s',
            'categorie_de_service' => 'required|string|max:255',
            'modele' => 'nullable|string|max:255',
            'numero_immatriculation' => 'nullable|string|max:255',
            'objet_du_RDV' => 'nullable|string|max:255',
        ]);

        $email = $request->email;
        $verificationCode = $request->verification_code;

        // Retrieve the stored verification code from cache
        $storedCode = Cache::get('verification_code_' . $email);

        if ($storedCode && $storedCode === $verificationCode) {
            // Verification successful, create the appointment
            $appointment = Appointment::create([
                'user_full_name' => $request->full_name,
                'user_phone' => $request->phone,
                'user_email' => $email,
                'garage_ref' => $request->garage_ref,
                'appointment_day' => $request->appointment_day,
                'appointment_time' => $request->appointment_time,
                'status' => 'en_cour',
                'categorie_de_service' => $request->categorie_de_service,
                'modele' => $request->modele,
                'numero_immatriculation' => $request->numero_immatriculation,
                'objet_du_RDV' => $request->objet_du_RDV,
            ]);

            // Clear the verification code from cache
            Cache::forget('verification_code_' . $email);

            return response()->json(['message' => 'Appointment booked successfully!']);
        } else {
            return response()->json(['message' => 'Invalid verification code.'], 400);
        }
    }
}