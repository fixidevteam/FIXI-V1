<?php

namespace App\Http\Controllers\FixiMarketAPi;

use App\Http\Controllers\Controller;
use App\Models\Garage;
use Illuminate\Http\Request;

class GarageController extends Controller
{
    public function index(Request $request)
    {
        $garages = garage::paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $garages
        ], 200);
    }
    public function show($id)
    {
        $garage = garage::find($id);

        if (!$garage) {
            return response()->json([
                'status' => 'error',
                'message' => 'Garage not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $garage
        ], 200);
    }
}
