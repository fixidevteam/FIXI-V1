<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\AppointmentVerificationMail;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\garage;
use App\Models\GarageSchedule;
use App\Models\GarageUnavailableTime;
use App\Models\jour_indisponible;
use App\Models\MarqueVoiture;
use App\Models\User;
use App\Notifications\ClientAddRdv;
use App\Notifications\ClientAddRdvManuelle;
use App\Notifications\GarageAcceptRdv;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class AppointmentController2 extends Controller
{
    public function SMS($phone, $message)
    {
        // Format phone number (remove leading 0, add 212 country code)
        $phone = preg_replace('/^0/', '212', $phone);

        // Prepare MoroccoSMS API parameters
        $params = [
            'sub_account' => '136_212',
            'sub_account_pass' => 'blconsulting876',
            'action' => 'send_sms',
            'sender_id' => 'FIXI',
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
        // fetch unavaible days 
        $disabledDates = jour_indisponible::where('garage_ref', $garage_ref)
            ->pluck('date')
            ->toArray();

        // Fetch services from the garages table (stored as JSON)
        $garage = Garage::where('ref', $garage_ref)->first();
        $services =  $garage ? $garage->domaines : [];

        // Fetch marques from the marque_voitures table
        $marques = MarqueVoiture::pluck('marque')->toArray();

        return response()->json([
            'available_dates' => $dates,
            'unavailable_dates' => $disabledDates,
            'services' => $services,
            'marques' => $marques,
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

        while ($fromTime->lessThan($toTime)) {
            $timeSlot = $fromTime->format('H:i:s');

            // Check if the slot falls within an unavailable period
            $isUnavailable = $unavailableTimes->contains(function ($unavailable) use ($timeSlot) {
                return $timeSlot >= $unavailable->unavailable_from && $timeSlot < $unavailable->unavailable_to;
            });

            // Check if the slot is already booked and is not cancelled
            $isBooked = Appointment::where('garage_ref', $garage_ref)
                ->where('appointment_day', $selectedDate)
                ->where('appointment_time', $timeSlot)
                ->where('status', '!=', 'annulé') // Ensure only active bookings block slots
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

    // new method :
    // public function getAvailableDatesShort2(Request $request)
    // {
    //     $garage_ref = $request->query('garage_ref');

    //     // Fetch all schedules for this garage
    //     $schedules = GarageSchedule::where('garage_ref', $garage_ref)->get();

    //     $availability = [];
    //     foreach ($schedules as $schedule) {
    //         $dayOfWeek = $schedule->available_day;
    //         $availability[$dayOfWeek] = true;
    //     }

    //     // Generate available dates for the next 7 days (like in the screenshot)
    //     $dates = [];
    //     $today = Carbon::today();
    //     for ($i = 0; $i < 7; $i++) {
    //         $date = $today->copy()->addDays($i);
    //         $dayOfWeek = $date->dayOfWeek;

    //         if (isset($availability[$dayOfWeek])) {
    //             $dates[] = [
    //                 'date' => $date->format('Y-m-d'),
    //                 'day_name' => $date->shortDayName, // "dim", "lun", etc.
    //                 'day_number' => $date->day,
    //                 'month_short' => $date->shortMonthName, // "avr", "mai", etc.
    //             ];
    //         }
    //     }

    //     // Fetch unavailable days
    //     $disabledDates = jour_indisponible::where('garage_ref', $garage_ref)
    //         ->pluck('date')
    //         ->toArray();

    //     // Fetch services from the garages table (stored as JSON)
    //     $garage = Garage::where('ref', $garage_ref)->first();
    //     $services =  $garage ? $garage->services : [];

    //     // Fetch marques from the marque_voitures table
    //     $marques = MarqueVoiture::pluck('marque')->toArray();


    //     return response()->json([
    //         'available_dates' => $dates,
    //         'unavailable_dates' => $disabledDates,
    //         'services' => $services,
    //         'marques' => $marques,
    //     ]);
    // }
    public function getAvailableDatesShort2(Request $request)
    {
        $garage_ref = $request->query('garage_ref');

        // Fetch all schedules for this garage
        $schedules = GarageSchedule::where('garage_ref', $garage_ref)->get();

        // Create array of available days (0=Sunday, 6=Saturday)
        $availableDays = [];
        foreach ($schedules as $schedule) {
            $availableDays[] = $schedule->available_day;
        }

        // Fetch unavailable specific dates
        $disabledDates = jour_indisponible::where('garage_ref', $garage_ref)
            ->pluck('date')
            ->toArray();

        // Generate available dates for the next 7 days
        $dates = [];
        $today = Carbon::today();
        for ($i = 1; $i <= 7; $i++) {
            $date = $today->copy()->addDays($i);
            $dayOfWeek = $date->dayOfWeek;
            $dateString = $date->format('Y-m-d');

            // Check if day of week is available and date is not disabled
            if (in_array($dayOfWeek, $availableDays)) {
                // Only add if date is not in disabled dates
                if (!in_array($dateString, $disabledDates)) {
                    $dates[] = [
                        'date' => $dateString,
                        'day_name' => $date->shortDayName,
                        'day_number' => $date->day,
                        'month_short' => $date->shortMonthName,
                    ];
                }
            }
        }

        // Fetch services from the garages table
        $garage = Garage::where('ref', $garage_ref)->first();
        $services = $garage ? $garage->domaines : [];

        // Fetch marques from the marque_voitures table
        $marques = MarqueVoiture::pluck('marque')->toArray();

        return response()->json([
            'available_dates' => $dates,
            'unavailable_dates' => $disabledDates,
            'services' => $services,
            'marques' => $marques,
        ]);
    }

    public function getTimeSlotsShort2(Request $request)
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

        // Generate available time slots (every 20 minutes like in the screenshot)
        $fromTime = Carbon::createFromFormat('H:i:s', $schedule->available_from);
        $toTime = Carbon::createFromFormat('H:i:s', $schedule->available_to);
        $slots = [];

        while ($fromTime->lessThan($toTime)) {
            $timeSlot = $fromTime->format('H:i:s');
            $displayTime = $fromTime->format('H:i'); // Format without seconds

            // Check if the slot falls within an unavailable period
            $isUnavailable = $unavailableTimes->contains(function ($unavailable) use ($timeSlot) {
                return $timeSlot >= $unavailable->unavailable_from && $timeSlot < $unavailable->unavailable_to;
            });

            // Check if the slot is already booked and is not cancelled
            $isBooked = Appointment::where('garage_ref', $garage_ref)
                ->where('appointment_day', $selectedDate)
                ->where('appointment_time', $timeSlot)
                ->where('status', '!=', 'annulé')
                ->exists();

            if (!$isUnavailable && !$isBooked) {
                $slots[] = $displayTime;
            }

            $fromTime->addHour(); // 60-minute intervals 
        }

        return response()->json([
            'time_slots' => $slots,
        ]);
    }
    // close new method

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
            'modele' => 'nullable|string|max:255',
            'objet_du_RDV' => 'nullable|string|max:255',
            'vin' => 'nullable|string|max:255',
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
        $verificationCode = mt_rand(100000, 999999);
        $fullName = $request->full_name;
        $phone = $request->phone;

        // Store the verification code in cache with an expiration time (e.g., 5 minutes)
        Cache::put('verification_code_' . $phone, $verificationCode, now()->addMinutes(5));

        // Send verification SMS via MoroccoSMS
        $message = "Bonjour " . $fullName . ", votre code de vérification est " . $verificationCode . ". Veuillez l'utiliser pour confirmer votre rendez-vous.\nFIXI.MA";
        $this->SMS($phone, $message);

        return response()->json([
            'message' => 'Verification code sent to your phone. Please enter the code to confirm your appointment.',
            'status' => 'verification_required',
        ]);
    }

    public function verifyAppointment(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'nullable|email|max:255',
            'verification_code' => 'required|digits:6',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'garage_ref' => 'required|string|max:255',
            'appointment_day' => 'required|date',
            'appointment_time' => 'required|date_format:H:i:s',
            'categorie_de_service' => 'required|string|max:255',
            'modele' => 'nullable|string|max:255',
            'objet_du_RDV' => 'nullable|string|max:255',
            'vin' => 'nullable|string|max:255',
        ]);

        $email = $request->email;
        $phone = $request->phone;
        $verificationCode = trim($request->verification_code);
        $garage = garage::where("ref", $request->garage_ref)->first();

        // Retrieve the stored verification code from cache using phone number
        $storedCode = Cache::get('verification_code_' . $phone);

        if ($storedCode && strval($storedCode) === strval($verificationCode)) {
            // Verification successful, create the appointment
            if ($garage->confirmation === 'automatique') {
                $status = "confirmé";
            } else {
                $status = "en cours";
            }
            $appointment = Appointment::create([
                'user_full_name' => $request->full_name,
                'user_phone' => $request->phone,
                'user_email' => $email,
                'garage_ref' => $request->garage_ref,
                'appointment_day' => $request->appointment_day,
                'appointment_time' => $request->appointment_time,
                'status' => $status,
                'categorie_de_service' => $request->categorie_de_service,
                'modele' => $request->modele,
                'objet_du_RDV' => $request->objet_du_RDV,
                'vin' => $request->vin,
            ]);

            // Clear the verification code from cache
            Cache::forget('verification_code_' . $phone);

            $existEmail = User::where('email', $email)->first();

            // Send SMS to garage
            $url = route('mechanic.reservation.show', $appointment);
            $message_gar = "Bonjour " . $garage->name . "\nVous venez de recevoir une demande de RDV :\n" . $url . "\n\nFIXI.MA";
            $garage_phone = $garage->telephone;
            $this->SMS($garage_phone, $message_gar);

            if ($existEmail) {
                if ($appointment->status === "confirmé") {
                    // send email to garage :
                    if ($garage) {
                        $mechanics = $garage->mechanics;
                        if ($mechanics->count() > 0) {
                            foreach ($mechanics as $mechanic) {
                                Notification::route('mail', $mechanic->email)
                                    ->notify(new ClientAddRdv($appointment));
                            }
                        }
                    }

                    // send email to user
                    Notification::route('mail', $appointment->user_email)
                        ->notify(new GarageAcceptRdv($appointment, 'la réservation a été confirmée par le garage'));

                    // send SMS TO USER
                    $message_user  = "Bonjour " . $appointment->user_full_name . "\nVotre RDV a été confirmé.\nRDV à " . \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') . " le " . \Carbon\Carbon::parse($appointment->appointment_day)->format('d/m/Y') . "\nChez " . $garage->name . "\nFIXI.MA";
                    $this->SMS($appointment->user_phone, $message_user);
                } elseif ($appointment->status === "en cours") {
                    // send email to garage
                    if ($garage) {
                        $mechanics = $garage->mechanics;
                        if ($mechanics->count() > 0) {
                            foreach ($mechanics as $mechanic) {
                                Notification::route('mail', $mechanic->email)
                                    ->notify(new ClientAddRdv($appointment));
                            }
                        }
                    }

                    // send email to client 
                    Notification::route('mail', $appointment->user_email)
                        ->notify(new ClientAddRdvManuelle($appointment));

                    //SEND SMS TO USER
                    $message_user  = "Bonjour " . $appointment->user_full_name . "\nVotre RDV est en cours de confirmation par le garage.\nRDV à " . \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') . " le " . \Carbon\Carbon::parse($appointment->appointment_day)->format('d/m/Y') . "\nChez " . $garage->name . "\nFIXI.MA";
                    $this->SMS($appointment->user_phone, $message_user);
                }
                return response()->json(['message' => 'Appointment booked successfully!', 'account' => true, 'ref' => $garage->ref, 'appointment' => $appointment, 'garage' => $garage]);
            } else {
                return response()->json(['message' => 'Appointment booked successfully!', 'account' => false, 'ref' => $garage->ref, 'appointment' => $appointment, 'garage' => $garage]);
            }
        } else {
            return response()->json(['message' => 'Invalid verification code.'], 400);
        }
    }
}