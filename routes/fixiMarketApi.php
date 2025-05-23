<?php

use App\Http\Controllers\FixiMarketAPi\GarageController;
use Illuminate\Support\Facades\Route;

Route::controller(GarageController::class)->group(function () {
    Route::get('/garages', 'index');
    Route::get('/garages/{garage}', 'show');
});
