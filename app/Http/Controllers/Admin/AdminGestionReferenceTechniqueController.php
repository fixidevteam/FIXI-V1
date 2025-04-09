<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModeleVoiture;
use App\Models\ReferenceTechnique;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminGestionReferenceTechniqueController extends Controller
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
        $modeles = ModeleVoiture::all();
        return view('admin.gestionReferenceTechnique.create', compact('modeles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'modele_id' => 'required|exists:modele_voitures,id',
            'reference_technique' => [
                'required',
                'string',
                'max:50',
                Rule::unique('reference_techniques')
                    ->where('modele_id', $request->modele_id) // Unique per modele_id
                    ->whereNull('deleted_at'), // Ignore soft-deleted records
            ],
            'motorisation' => 'required|string',
            'boite_vitesse' => 'required|string',
            'puissance_thermique' => 'required|integer|min:1',
            'puissance_fiscale' => 'required|integer|min:1',
            'cylindree' => 'required|numeric|min:0',
        ]);

        // Création de la référence technique
        ReferenceTechnique::create($validatedData);

        // Redirection avec un message de succès
        session()->flash('success', 'Référence technique ajoutée');
        session()->flash('subtitle', 'La référence technique a été ajoutée avec succès.');
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
        $referenceTechnique = ReferenceTechnique::find($id);
        $modeles = ModeleVoiture::all();
        if ($referenceTechnique) {
            return view('admin.gestionReferenceTechnique.edit', compact('referenceTechnique', 'modeles'));
        }
        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the reference or fail (returns 404 if not found)
        $reference = ReferenceTechnique::findOrFail($id);

        // Validate data (with unique rule ignoring current ID)
        $validatedData = $request->validate([
            'modele_id' => 'required|exists:modele_voitures,id',
            'reference_technique' => [
                'required',
                'string',
                'max:50',
                Rule::unique('reference_techniques')
                    ->where('modele_id', $request->modele_id)
                    ->ignore($id) // Ignore current record during update
                    ->whereNull('deleted_at'),
            ],
            'motorisation' => 'required|string',
            'boite_vitesse' => 'required|string',
            'puissance_thermique' => 'required|integer|min:1',
            'puissance_fiscale' => 'required|integer|min:1',
            'cylindree' => 'required|numeric|min:0',
        ]);

        // Update the reference
        $reference->update($validatedData);

        // Success message
        session()->flash('success', 'Référence technique modifiée');
        session()->flash('subtitle', 'La référence technique a été modifiée avec succès.');

        return redirect()->route('admin.gestionMarque.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $referenceTechnique = ReferenceTechnique::find($id);
        if ($referenceTechnique) {
            $referenceTechnique->delete();
        }
        session()->flash('success', 'Référence technique supprimée');
        session()->flash('subtitle', 'La référence technique a été supprimée avec succès.');
        return redirect()->route('admin.gestionMarque.index');
    }
}