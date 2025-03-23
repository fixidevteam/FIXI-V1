<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domaine;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminGestionServiceController extends Controller
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
        $domaines = Domaine::all();
        return view('admin.gestionService.create', compact('domaines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'domaine_id' => 'required|exists:domaines,id',
            'service' => ['required', 'string', 'max:255',  Rule::unique('services')->whereNull('deleted_at')],
        ]);

        // Création du service
        Service::create([
            'domaine_id' => $request->domaine_id,
            'service' => $request->service,
        ]);

        // Redirection avec un message de succès
        session()->flash('success', 'service ajouté');
        session()->flash('subtitle', 'service a été ajouté avec succès.');
        return redirect()->route('admin.gestionDomaine.index');
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
        $service = Service::find($id);
        $domaines = Domaine::all();
        if ($service) {
            return view('admin.gestionService.edit', compact('service', 'domaines'));
        }
        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $service = Service::find($id);
        $newservice = $request->validate([
            'domaine_id' => 'required|exists:domaines,id',
            'service' => ['required', 'string', 'max:255',  Rule::unique('services')->whereNull('deleted_at')],

        ]);

        if ($service) {
            $service->update($newservice);
            session()->flash('success', 'Service ajouté');
            session()->flash('subtitle', 'Service a été ajouté avec succès.');
        }
        return redirect()->route('admin.gestionDomaine.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Service::find($id);
        if ($service) {
            $service->delete();
        }
        session()->flash('success', 'Service supprimée');
        session()->flash('subtitle', 'Service a été supprimée avec succès.');
        return redirect()->route('admin.gestionDomaine.index');
    }
}
