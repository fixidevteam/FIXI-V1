<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarqueVoiture;
use App\Models\ModeleVoiture;
use Database\Seeders\MarqueSeeder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminGestionModeleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $marques = MarqueVoiture::all();
        return view('admin.gestionModele.create', compact('marques'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'marque_id' => 'required|exists:marque_voitures,id',
            'modele' => [
                'required',
                'string',
                'max:255',
                Rule::unique('modele_voitures', 'modele')->whereNull('deleted_at'),
            ],
        ]);

        // Création du modèle
        ModeleVoiture::create([
            'marque_id' => $request->marque_id,
            'modele' => $request->modele,
        ]);

        // Redirection avec un message de succès
        session()->flash('success', 'Modèle ajouté');
        session()->flash('subtitle', 'Modèle a été ajouté avec succès.');
        return redirect()->route('admin.gestionMarque.index');
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
        $modele = ModeleVoiture::find($id);
        $marques = MarqueVoiture::all();
        if ($modele) {
            return view('admin.gestionModele.edit', compact('modele', 'marques'));
        }
        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $modele = ModeleVoiture::find($id);
        $newmodele = $request->validate([
            'marque_id' => ['required'],
            'modele' => [
                'required',
                'string',
                'max:255',
                Rule::unique('modele_voitures', 'modele')->whereNull('deleted_at'),
            ],
        ]);

        if ($modele) {
            $modele->update($newmodele);
            session()->flash('success', 'Modèle ajouté');
            session()->flash('subtitle', 'Modèle a été ajouté avec succès.');
        }
        return redirect()->route('admin.gestionMarque.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $modele = ModeleVoiture::find($id);
        if ($modele) {
            $modele->delete();
        }
        session()->flash('success', 'Modèle supprimée');
        session()->flash('subtitle', 'Modèle a été supprimée avec succès.');
        return redirect()->route('admin.gestionMarque.index');
    }
}