<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\garage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth()->user()->email;

        // Fetch paginated appointments ordered by latest
        $appointments = Appointment::where('user_email', $user)
            ->join('garages', 'appointments.garage_ref', '=', 'garages.ref')
            ->select('appointments.*', 'garages.name as garage_name')
            ->orderBy('appointments.created_at', 'desc') // Order by latest
            ->paginate(10); // Paginate with 10 records per page

        // Check if appointments exist
        if ($appointments->isEmpty()) {
            return back()->with('error', 'Vous n\'avez aucun rendez-vous.');
        }

        // Pass the data to the view
        return view('userRdv.index', compact('appointments'));
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
        $userEmail = Auth()->user()->email;

        // Find the appointment by id and user email
        $appointment = Appointment::where('id', $id)
            ->where('user_email', $userEmail)
            ->first();

        if (!$appointment) {
            return back()->with('error', 'Rendez-vous introuvable.');
        }

        $garage = garage::where('ref', $appointment->garage_ref)->first();

        return view('userRdv.show', compact('appointment', 'garage'));
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}