<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\jour_indisponible;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JourIndisponibleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ref = Auth::user()->garage->ref;
        $disabledDates = jour_indisponible::where('garage_ref', $ref);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mechanic.jour-indisponible.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ref = Auth::user()->garage->ref;
        $data = $request->validate([
            'date' => 'required|date',
        ]);
        $data['garage_ref'] = $ref;
        jour_indisponible::create($data);
        session()->flash('success', 'Lejour ajouté');
        session()->flash('subtitle', 'Le jour a été ajouté avec succès.');
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
    public function edit(string $id)
    {
        $ref = Auth::user()->garage->ref;
        $jour = jour_indisponible::where('id', $id)->where('garage_ref', $ref)->first();
        if ($jour) {
            return view('mechanic.jour-indisponible.edit', compact('jour'));
        } else {
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ref = Auth::user()->garage->ref;
        $jour = jour_indisponible::where('id', $id)->where('garage_ref', $ref)->first();
        $data = $request->validate([
            'date' => 'required|date',
        ]);
        $data['garage_ref'] = $ref;
        if ($jour) {
            $jour->update($data);
            session()->flash('success', 'Le jour modifié');
            session()->flash('subtitle', 'Le jour a été modifié avec succès.');
            return redirect()->route('mechanic.calendrier.index');
        }else{
             return back();   
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jour = jour_indisponible::find($id);
        $jour->delete();
        session()->flash('success', 'jour supprimée');
        session()->flash('subtitle', 'le jour a été supprimée avec succès.');
        return back();
    }
}
