<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\garage;
use App\Models\GarageSchedule;
use App\Models\GarageUnavailableTime;
use Illuminate\Http\Request;

class AdminGestionCalendrierController extends Controller
{
    // Define the constant for repeated messages
    const GARAGE_NOT_FOUND = 'Garage introuvable';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $garages = garage::whereNull('user_id')->get();
        return view('admin.gestionCalendrier.index', compact('garages'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create($garage_id)
    {
        $garage = garage::find($garage_id);

        if (empty($garage)) {
            return back();
        }

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

        return view('admin.gestionCalendrier.create', compact('daysOfWeek', 'garage'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $garage_id)
    {
        $garage = Garage::findOrFail($garage_id);

        if (!$garage) {
            return back()->with('error', self::GARAGE_NOT_FOUND);
        }

        $request->validate([
            'available_day' => 'required|array|min:1', // Allow multiple days
            'available_from' => 'required|date_format:H:i',
            'available_to' => 'required|date_format:H:i|after:available_from',
            'unavailable_from.*' => 'nullable|date_format:H:i',
            'unavailable_to.*' => 'nullable|date_format:H:i|after:unavailable_from.*',
        ]);

        // Check if any of the selected days already exist in the database
        $existingDays = GarageSchedule::where('garage_ref', $garage->ref)
            ->whereIn('available_day', $request->available_day)
            ->pluck('available_day')
            ->toArray();

        if (!empty($existingDays)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['available_day' => 'Le jour sélectionné existe déjà dans le calendrier. Veuillez choisir un autre jour.']);
        }

        // Store new schedules
        foreach ($request->available_day as $day) {
            GarageSchedule::create([
                'garage_ref' => $garage->ref,
                'available_day' => $day,
                'available_from' => $request->available_from,
                'available_to' => $request->available_to,
            ]);
        }

        // Store unavailable times
        if ($request->unavailable_from && $request->unavailable_to) {
            foreach ($request->unavailable_from as $key => $unavailable_from) {
                if (!empty($unavailable_from) && !empty($request->unavailable_to[$key])) {
                    GarageUnavailableTime::create([
                        'garage_ref' => $garage->ref,
                        'unavailable_day' => $request->available_day[0], // Store the first available day
                        'unavailable_from' => $unavailable_from,
                        'unavailable_to' => $request->unavailable_to[$key],
                    ]);
                }
            }
        }

        session()->flash('success', 'Calendrier ajouté avec succès.');
        return redirect()->route('admin.gestionCalendrier.show', $garage->id);
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $garage = garage::findOrFail($id);
        $schedules = GarageSchedule::where('garage_ref', $garage->ref)->get();
        $unavailableTimes = GarageUnavailableTime::where('garage_ref', $garage->ref)->get();

        $daysOfWeek = [
            '0' => 'Dimanche',
            '1' => 'Lundi',
            '2' => 'Mardi',
            '3' => 'Mercredi',
            '4' => 'Jeudi',
            '5' => 'Vendredi',
            '6' => 'Samedi'
        ];

        return view('admin.gestionCalendrier.show', compact('garage', 'schedules', 'unavailableTimes', 'daysOfWeek'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $schedule = GarageSchedule::findOrFail($id);
        $garage = garage::where('ref', $schedule->garage_ref)->first();

        if (!$garage) {
            return redirect()->route('admin.gestionCalendrier.index')->with('error', self::GARAGE_NOT_FOUND);
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

        return view('admin.gestionCalendrier.edit', compact('schedule', 'garage', 'unavailableTimes', 'daysOfWeek'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $schedule = GarageSchedule::findOrFail($id);
        $garage = garage::where('ref', $schedule->garage_ref)->first();

        if (!$garage) {
            return redirect()->route('admin.gestionCalendrier.index')->with('error', self::GARAGE_NOT_FOUND);
        }

        $request->validate([
            'available_day' => 'required|integer|between:0,6', // Ensure valid day selection
            'available_from' => 'required',
            'available_to' => 'required|after:available_from',
            'unavailable_from.*' => 'nullable',
            'unavailable_to.*' => 'nullable|after:unavailable_from.*',
        ]);

        // Check for duplicate days
        $existingSchedule = GarageSchedule::where('garage_ref', $garage->ref)
            ->where('available_day', $request->available_day)
            ->where('id', '!=', $id) // Exclude current schedule
            ->first();

        if ($existingSchedule) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['available_day' => 'Un calendrier existe déjà pour ce jour.']);
        }

        // Update the schedule
        $schedule->update([
            'available_day' => $request->available_day,
            'available_from' => $request->available_from,
            'available_to' => $request->available_to,
        ]);

        // Remove old unavailable times and insert new ones
        GarageUnavailableTime::where('garage_ref', $garage->ref)
            ->where('unavailable_day', $schedule->available_day)
            ->delete();

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

        return redirect()->route('admin.gestionCalendrier.show', $garage->id)->with('success', 'Calendrier mis à jour avec succès.');
    }

    /**
     * Update the status of appointment.
     */
    public function updateStatus(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $schedule = GarageSchedule::findOrFail($id);
        $garage = garage::where('ref', $schedule->garage_ref)->first();

        if (!$garage) {
            return redirect()->route('admin.gestionCalendrier.index')->with('error', self::GARAGE_NOT_FOUND);
        }

        // Delete associated unavailable times
        GarageUnavailableTime::where('garage_ref', $garage->ref)
            ->where('unavailable_day', $schedule->available_day)
            ->delete();

        // Delete schedule
        $schedule->delete();

        return redirect()->route('admin.gestionCalendrier.show', $garage->id)->with('success', 'Calendrier supprimé avec succès.');
    }
}
