<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\garage;
use App\Models\GarageSchedule;
use App\Models\GarageUnavailableTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MechanicCalendrierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $garage = Garage::where('id', $user->garage_id)->first();

        if (!$garage) {
            return redirect()->back()->with('error', 'Garage not found.');
        }

        // Fetch schedules
        $schedules = GarageSchedule::where('garage_ref', $garage->ref)->get();

        // Fetch unavailable times
        $unavailableTimes = GarageUnavailableTime::where('garage_ref', $garage->ref)->get();

        // Days of the week
        $daysOfWeek = [
            '0' => 'Dimanche',
            '1' => 'Lundi',
            '2' => 'Mardi',
            '3' => 'Mercredi',
            '4' => 'Jeudi',
            '5' => 'Vendredi',
            '6' => 'Samedi',
        ];

        return view('mechanic.calendrier.index', compact('schedules', 'unavailableTimes', 'daysOfWeek'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Days of the week
        $daysOfWeek = [
            '0' => 'Dimanche',
            '1' => 'Lundi',
            '2' => 'Mardi',
            '3' => 'Mercredi',
            '4' => 'Jeudi',
            '5' => 'Vendredi',
            '6' => 'Samedi',
        ];
        return view('mechanic.calendrier.create', compact('daysOfWeek'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     // Get the authenticated user
    //     $user = Auth::user();

    //     // Fetch the garage associated with the mechanic
    //     $garage = garage::where('id', $user->garage_id)->first();

    //     // Validate the request data
    //     $request->validate([
    //         'available_day' => ['required', 'integer', 'between:0,6'], // 0 = Sunday, 1 = Monday, etc.
    //         'available_from' => ['required', 'date_format:H:i'], // 24-hour format
    //         'available_to' => ['required', 'date_format:H:i', 'after:available_from'], // 24-hour format and after available_from
    //     ]);

    //     // Check if a schedule already exists for the garage and day
    //     $existingSchedule = GarageSchedule::where('garage_ref', $garage->ref)
    //         ->where('available_day', $request->available_day)
    //         ->first();

    //     if ($existingSchedule) {
    //         // If a schedule exists, return with an error message
    //         return redirect()->back()
    //             ->withInput() // Preserve the user's input
    //             ->withErrors(['available_day' => 'Un calendrier existe déjà pour ce jour.']); // Error message for available_day
    //     }

    //     // Create a new GarageSchedule record
    //     GarageSchedule::create([
    //         'garage_ref' => $garage->ref, // Use the garage reference
    //         'available_day' => $request->available_day, // Day of the week (0-6)
    //         'available_from' => $request->available_from, // Start time
    //         'available_to' => $request->available_to, // End time
    //     ]);

    //     // Flash success messages
    //     session()->flash('success', 'Calendrier ajoutée');
    //     session()->flash('subtitle', 'Votre Calendrier a été ajoutée avec succès à la liste.');

    //     return redirect()->route('mechanic.calendrier.index');
    // }
    public function store(Request $request)
    {
        $user = Auth::user();
        $garage = Garage::where('id', $user->garage_id)->first();

        $request->validate([
            'available_day' => ['required', 'integer', 'between:0,6'],
            'available_from' => ['required', 'date_format:H:i'],
            'available_to' => ['required', 'date_format:H:i', 'after:available_from'],
            'unavailable_from.*' => ['nullable', 'date_format:H:i'],
            'unavailable_to.*' => ['nullable', 'date_format:H:i', 'after:unavailable_from.*'],
        ]);

        // Store available time
        GarageSchedule::create([
            'garage_ref' => $garage->ref,
            'available_day' => $request->available_day,
            'available_from' => $request->available_from,
            'available_to' => $request->available_to,
        ]);

        // Store multiple unavailable times
        if ($request->unavailable_from && $request->unavailable_to) {
            foreach ($request->unavailable_from as $key => $unavailable_from) {
                if (!empty($unavailable_from) && !empty($request->unavailable_to[$key])) {
                    GarageUnavailableTime::create([
                        'garage_ref' => $garage->ref,
                        'unavailable_day' => $request->available_day,
                        'unavailable_from' => $unavailable_from,
                        'unavailable_to' => $request->unavailable_to[$key],
                    ]);
                }
            }
        }

        session()->flash('success', 'Calendrier ajouté avec indisponibilités multiples');
        return redirect()->route('mechanic.calendrier.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id)
    // {
    //     // Get the authenticated user
    //     $user = Auth::user();

    //     // Fetch the garage associated with the mechanic
    //     $garage = garage::where('id', $user->garage_id)->first();

    //     // Fetch the schedule by ID
    //     $schedule = GarageSchedule::find($id);

    //     // Check if the schedule exists
    //     if (!$schedule) {
    //         return redirect()->route('mechanic.calendrier.index')
    //             ->with('error', 'Schedule not found.');
    //     }

    //     // Check if the schedule belongs to the authenticated user's garage
    //     if ($schedule->garage_ref !== $garage->ref) {
    //         return redirect()->route('mechanic.calendrier.index')
    //             ->with('error', 'You are not authorized to edit this schedule.');
    //     }

    //     // Days of the week
    //     $daysOfWeek = [
    //         '0' => 'Dimanche',
    //         '1' => 'Lundi',
    //         '2' => 'Mardi',
    //         '3' => 'Mercredi',
    //         '4' => 'Jeudi',
    //         '5' => 'Vendredi',
    //         '6' => 'Samedi',
    //     ];

    //     // Pass the schedule and days of the week to the view
    //     return view('mechanic.calendrier.edit', compact('schedule', 'daysOfWeek'));
    // }
    public function edit($id)
    {
        $user = Auth::user();
        $garage = Garage::where('id', $user->garage_id)->first();

        $schedule = GarageSchedule::find($id);
        if (!$schedule || $schedule->garage_ref !== $garage->ref) {
            return redirect()->route('mechanic.calendrier.index')->with('error', 'Not authorized.');
        }

        $unavailableTimes = GarageUnavailableTime::where('garage_ref', $garage->ref)
            ->where('unavailable_day', $schedule->available_day)
            ->get();

        $daysOfWeek = [
            '0' => 'Dimanche',
            '1' => 'Lundi',
            '2' => 'Mardi',
            '3' => 'Mercredi',
            '4' => 'Jeudi',
            '5' => 'Vendredi',
            '6' => 'Samedi',
        ];

        return view('mechanic.calendrier.edit', compact('schedule', 'unavailableTimes', 'daysOfWeek'));
    }


    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $id)
    // {
    //     // Fetch the schedule by ID
    //     $schedule = GarageSchedule::find($id);

    //     // Check if the schedule exists
    //     if (!$schedule) {
    //         return redirect()->route('mechanic.calendrier.index')
    //             ->with('error', 'Schedule not found.');
    //     }

    //     // Validate the request data
    //     $request->validate([
    //         'available_day' => ['required', 'integer', 'between:0,6'], // 0 = Sunday, 1 = Monday, etc.
    //         'available_from' => ['required', 'date_format:H:i'], // 24-hour format
    //         'available_to' => ['required', 'date_format:H:i', 'after:available_from'], // 24-hour format and after available_from
    //     ]);

    //     // Check if a schedule already exists for the garage and day (excluding the current schedule)
    //     $existingSchedule = GarageSchedule::where('garage_ref', $schedule->garage_ref)
    //         ->where('available_day', $request->available_day)
    //         ->where('id', '!=', $id) // Exclude the current schedule
    //         ->first();

    //     if ($existingSchedule) {
    //         // If a schedule exists, return with an error message
    //         return redirect()->back()
    //             ->withInput() // Preserve the user's input
    //             ->withErrors(['available_day' => 'Un calendrier existe déjà pour ce jour.']); // Error message for available_day
    //     }

    //     // Update the schedule
    //     $schedule->update([
    //         'available_day' => $request->available_day,
    //         'available_from' => $request->available_from,
    //         'available_to' => $request->available_to,
    //     ]);

    //     // Flash success messages
    //     session()->flash('success', 'Calendrier mis à jour');
    //     session()->flash('subtitle', 'Votre Calendrier a été mis à jour avec succès.');

    //     // Redirect to the schedules index page
    //     return redirect()->route('mechanic.calendrier.index');
    // }
    public function update(Request $request, $id)
    {
        $schedule = GarageSchedule::find($id);
        if (!$schedule) {
            return redirect()->route('mechanic.calendrier.index')->with('error', 'Schedule not found.');
        }
        // Debug: Check request data
        // dd($request->all());

        // Validate form input
        $request->validate([
            'available_day' => 'required|integer|between:0,6',
            'available_from' => 'required',
            'available_to' => 'required|after:available_from',
            'unavailable_from.*' => 'nullable',
            'unavailable_to.*' => 'nullable|after:unavailable_from.*',
        ]);


        // dd($schedule);

        // Update schedule
        $schedule->update([
            'available_day' => $request->available_day,
            'available_from' => $request->available_from,
            'available_to' => $request->available_to,
        ]);

        // dd($schedule);

        // Remove old unavailable times and insert new ones
        GarageUnavailableTime::where('garage_ref', $schedule->garage_ref)
            ->where('unavailable_day', $schedule->available_day)
            ->delete();

        // dd($request->unavailable_from, $request->unavailable_to);

        if ($request->unavailable_from && $request->unavailable_to) {
            foreach ($request->unavailable_from as $key => $unavailable_from) {
                if (!empty($unavailable_from) && !empty($request->unavailable_to[$key])) {
                    GarageUnavailableTime::create([
                        'garage_ref' => $schedule->garage_ref,
                        'unavailable_day' => $request->available_day,
                        'unavailable_from' => $unavailable_from,
                        'unavailable_to' => $request->unavailable_to[$key],
                    ]);
                }
            }
        }

        session()->flash('success', 'Calendrier mis à jour avec indisponibilités multiples');
        return redirect()->route('mechanic.calendrier.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Fetch the garage associated with the authenticated user
        $garage = garage::where('id', $user->garage_id)->first();

        // Fetch the schedule by ID
        $schedule = GarageSchedule::find($id);

        // Check if the schedule exists
        if (!$schedule) {
            return redirect()->route('mechanic.calendrier.index')
                ->with('error', 'Schedule not found.');
        }

        // Check if the schedule belongs to the authenticated user's garage
        if ($schedule->garage_ref !== $garage->ref) {
            return redirect()->route('mechanic.calendrier.index')
                ->with('error', 'You are not authorized to delete this schedule.');
        }

        // Delete the schedule
        $schedule->delete();

        // Flash success messages
        session()->flash('success', 'Calendrier supprimée');
        session()->flash('subtitle', 'Votre Calendrier a été supprimée avec succès à la liste.');

        return redirect()->route('mechanic.calendrier.index');
    }
}