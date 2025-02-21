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
        return back();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return back();
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
    public function update(Request $request, $id)
    {
        $schedule = GarageSchedule::find($id);
        if (!$schedule) {
            return redirect()->route('mechanic.calendrier.index')->with('error', 'Schedule not found.');
        }

        // Validate form input
        $request->validate([
            'available_day' => 'required|integer|between:0,6',
            'available_from' => 'required',
            'available_to' => 'required|after:available_from',
            'unavailable_from.*' => 'nullable',
            'unavailable_to.*' => 'nullable|after:unavailable_from.*',
        ]);

        // Update schedule
        $schedule->update([
            'available_day' => $request->available_day,
            'available_from' => $request->available_from,
            'available_to' => $request->available_to,
        ]);

        // Remove old unavailable times and insert new ones
        GarageUnavailableTime::where('garage_ref', $schedule->garage_ref)
            ->where('unavailable_day', $schedule->available_day)
            ->delete();

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
        return back();
    }
}