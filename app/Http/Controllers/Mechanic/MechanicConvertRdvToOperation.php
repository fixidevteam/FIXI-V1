<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\garage;
use App\Models\MarqueVoiture;
use App\Models\Operation;
use App\Models\User;
use App\Models\Visit;
use App\Models\Voiture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MechanicConvertRdvToOperation extends Controller
{
    public function showForm($id)
    {
        $garage = Auth::user()->garage;
        $Appointment = Appointment::where('id', $id)->where('garage_ref', $garage->ref)->first();
        if ($Appointment->user_email !== NULL) {
            $client = User::where('email', $Appointment->user_email)->first();
        } else {
            $client = NULL;
        }

        $marques = MarqueVoiture::all();

        return view('mechanic.convertRdvToOperation.convert', compact('Appointment', 'client', 'marques'));
        // echo($client->voitures );

    }
    public function convert(Request $request)
    {
        if (!$request->has('voiture_id')) {
            $data = $request->validate([
                'part1' => ['required', 'digits_between:1,6'], // 1 to 6 digits
                'part2' => ['required', 'string', 'size:1'], // Single Arabic letter
                'part3' => ['required', 'digits_between:1,2'], // 1 to 2 digits
                'marque' => ['required', 'max:30'],
                'modele' => ['required', 'max:30'],
            ]);
        } else {
            $data = $request->validate([
                'voiture_id' => ['required'], // 1 to 6 digits
            ]);
        }

        if ($request->client_email !== NULL) {
            $tel = $request->client_tel;

            // Replace +212 at the beginning with 0
            if (strpos($tel, '+212') === 0) {
                $tel = preg_replace('/^\+212/', '0', $tel);
            }
            // Ngow query the client
            $client = User::where('telephone', $tel)->first();

            // $client = User::where('telephone', $request->client_tel)->first();
        } else {
            $client = NULL;
        }
        if (!$client) {
            $client = User::create(
                [
                    // 'email' => $request->client_email,
                    'name' => $request->client_name,
                    'telephone' => $request->client_tel,
                ]
            );
        }

        if ($request->has('voiture_id')) {
            $visit = Visit::create([
                'date' => $request->date_operation,
                'voiture_id' => $request->voiture_id,
                'garage_id' => Auth::user()->garage->id,
            ]);
            $operation = Operation::create([
                'categorie' => $request->categorie,
                'description' => $request->description,
                'date_operation' => $request->date_operation,
                'voiture_id' => $request->voiture_id,
                'garage_id' => Auth::user()->garage->id,
                'create_by' => 'garage',
                'visit_id' => $visit->id,
            ]);
        } else {
            $numeroImmatriculation = $request['part1'] . '-' . $request['part2'] . '-' . $request['part3'];
            $voiture = Voiture::create([
                'numero_immatriculation' => $numeroImmatriculation,
                'marque' => $request->marque,
                'modele' => $request->modele,
                'user_id' => $client->id
            ]);
            $visit = Visit::create([
                'date' => $request->date_operation,
                'voiture_id' => $voiture->id,
                'garage_id' => Auth::user()->garage->id,
            ]);
            $operation = Operation::create([
                'categorie' => $request->categorie,
                'description' => $request->description,
                'date_operation' => $request->date_operation,
                'voiture_id' => $voiture->id,
                'garage_id' => Auth::user()->garage->id,
                'create_by' => 'garage',
                'visit_id' => $visit->id,
            ]);
        }
        return redirect()->route('mechanic.visits.show', $visit);
    }
}
