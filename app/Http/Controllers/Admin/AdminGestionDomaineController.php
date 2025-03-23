<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domaine;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminGestionDomaineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $domaines = Domaine::all();
        $services = Service::all();
        return view('admin.gestionDomaine.index', compact('domaines', 'services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.gestionDomaine.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $domaine = $request->validate(
            [
                'domaine' => [
                    'required',
                    Rule::unique('domaines')->whereNull('deleted_at'),
                ],
            ]
        );

        if ($domaine) {
            Domaine::create($domaine);
            session()->flash('success', 'Domaine ajouté');
            session()->flash('subtitle', 'Domaine a été ajouté avec succès à la liste.');
            return redirect()->route('admin.gestionDomaine.index');
        }
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
        $domaine = Domaine::find($id);
        if ($domaine) {
            return view('admin.gestionDomaine.edit', compact('domaine'));
        }
        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $domaine = $request->validate(
            [
                'domaine' => [
                    'required',
                    Rule::unique('domaines')->whereNull('deleted_at'),
                ],
            ]
        );
        $targetdomaine = Domaine::find($id);

        if ($targetdomaine) {
            $targetdomaine->update($domaine);
        }
        session()->flash('success', 'Domaine mis à jour');
        session()->flash('subtitle', 'Domaine a été mis à jour avec succès.');
        return redirect()->route('admin.gestionDomaine.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $domaine = Domaine::find($id);

        if ($domaine) {
            // Delete all related services first
            $domaine->services()->delete();
            // Then delete the domaine
            $domaine->delete();

            session()->flash('success', 'Domaine supprimée');
            session()->flash('subtitle', 'Domaine a été supprimée avec succès.');
        } else {
            session()->flash('error', 'Domaine introuvable');
        }

        return redirect()->route('admin.gestionDomaine.index');
    }
}
