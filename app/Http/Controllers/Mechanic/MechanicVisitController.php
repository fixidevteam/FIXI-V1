<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\nom_categorie;
use App\Models\nom_operation;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MechanicVisitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Get visits for the user's garage
        $visits = Visit::where('garage_id', $user->garage->id)
            ->latest() // Orders by created_at by default
            ->get();

        return view('mechanic.visits.index', compact('visits'));
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
    public function show($id)
    {
        $user = Auth::user();

        // Try to find the visit in the mechanic's garage
        $visit = Visit::where('id', $id)
            ->where('garage_id', $user->garage_id)
            ->with(['voiture', 'garage', 'operations'])
            ->first();
        $nom_categories = nom_categorie::all();
        $nom_operations = nom_operation::all();
        // dd($visit->operations);
        // If visit not found or unauthorized, redirect back with error
        if (!$visit) {
            return back()->with('error', 'Visite introuvable ou accès non autorisé.')
                ->with('subtitle', 'Cette visite n\'existe pas dans votre garage.');
        }

        return view('mechanic.visits.show', compact('visit', 'nom_categories', 'nom_operations'));
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