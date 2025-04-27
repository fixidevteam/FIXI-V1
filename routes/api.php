<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AppointmentController2;
use App\Models\MarqueVoiture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/operations/{categorieId}', [App\Http\Controllers\DataController::class, 'getOperations']);
Route::get('/sous-operations/{operationId}', [App\Http\Controllers\DataController::class, 'getSousOperations']);
Route::get('/modele/{marque}', function ($marque) {
    $marque = MarqueVoiture::where('marque', $marque)->first();
    return response()->json($marque->modeles);
});

// Booking

Route::get('/available-dates', [AppointmentController::class, 'getAvailableDates']);
Route::get('/time-slots', [AppointmentController::class, 'getTimeSlots']);
Route::post('/book-appointment', [AppointmentController::class, 'bookAppointment']);
Route::post('/appointments/verify', [AppointmentController::class, 'verifyAppointment']);
Route::post('/resend-verification-code', [AppointmentController::class, 'resendVerificationCode'])
    ->middleware('throttle:3,10'); // 3 requests every 10 minutes

Route::get('/available-datesShort', [AppointmentController::class, 'getAvailableDatesShort2']);
Route::get('/time-slotsShort', [AppointmentController::class, 'getTimeSlotsShort']);

Route::get('/available-dates2', [AppointmentController2::class, 'getAvailableDates']);
Route::get('/time-slots2', [AppointmentController2::class, 'getTimeSlots']);
Route::post('/book-appointment2', [AppointmentController2::class, 'bookAppointment']);
Route::post('/appointments/verify2', [AppointmentController2::class, 'verifyAppointment']);

Route::get('/available-datesShort2', [AppointmentController2::class, 'getAvailableDatesShort2']);
Route::get('/time-slotsShort2', [AppointmentController2::class, 'getTimeSlotsShort2']);