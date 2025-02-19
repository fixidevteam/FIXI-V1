<?php

namespace App\Http\Controllers\TEST;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RendezVousController extends Controller
{
    public function index($year, $month)
    {
        $ref = Auth::user()->garage->ref;
        $rendez = Appointment::whereYear('appointment_day', $year)
            ->whereMonth('appointment_day', $month)->where('garage_ref',$ref)
            ->get();

        return response()->json($rendez);
    }
}
