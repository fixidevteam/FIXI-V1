<?php

use App\Http\Controllers\Mechanic\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Mechanic\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\getAnalyticsDataController;
use App\Http\Controllers\Mechanic\ChartContoller;
use App\Http\Controllers\Mechanic\ConfirmationRdv;
use App\Http\Controllers\Mechanic\ExportController;
use App\Http\Controllers\Mechanic\JourIndisponibleController;
use App\Http\Controllers\Mechanic\MechanicCalendrierController;
use App\Http\Controllers\Mechanic\MechanicClientController;
use App\Http\Controllers\Mechanic\MechanicOperatioController;
use App\Http\Controllers\Mechanic\MechanicVoitureController;
use App\Http\Controllers\Mechanic\ProfileController;
use App\Http\Controllers\Mechanic\mechanicDashboardController;
use App\Http\Controllers\Mechanic\MechanicPromotionController;
use App\Http\Controllers\Mechanic\MechanicReservationController;
use App\Http\Controllers\TEST\RendezVousController;
use App\Models\Appointment;
use App\Models\Mechanic;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:mechanic')->prefix('fixi-pro')->name('mechanic.')->group(function () {
    // Route::get('register', [RegisteredUserController::class, 'create'])
    // ->name('register');

    // Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware(['auth:mechanic', 'checkMechanicStatus'])->prefix('fixi-pro')->name('mechanic.')->group(function () {
    Route::get('/dashboard', [mechanicDashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // routes func :
    Route::resource('/operations', MechanicOperatioController::class);
    Route::resource('/voitures', MechanicVoitureController::class);
    Route::resource('/clients', MechanicClientController::class);
    Route::resource('/promotions', MechanicPromotionController::class);
    Route::resource('/calendrier', MechanicCalendrierController::class);
    Route::resource('/reservation', MechanicReservationController::class);
    Route::get('/list', [MechanicReservationController::class, 'list'])
        ->name('reservation.list');
    Route::patch('/reservation/{id}/update-status', [MechanicReservationController::class, 'updateStatus'])
        ->name('reservation.updateStatus');
    Route::get('/analytics-data', [getAnalyticsDataController::class, 'getAnalyticsData'])->name('analytics.data');
    Route::get('/chart', [ChartContoller::class, 'index'])->name('chart');
    Route::get('/mechanic/voitures/export/{voitureId}', [ExportController::class, 'exportOperations'])->name('voitures.export');

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::resource('/jour-indisponible', JourIndisponibleController::class);

    Route::get('/test', function () {
        return view('mechanic.test1');
    });
    Route::get('/confirmation',[ConfirmationRdv::class,'index'])->name('confirmation');
    Route::put('/confirmation/{id}/accepter',[ConfirmationRdv::class,'accepter'])->name('confirmation.accepter');
    Route::put('/confirmation/{id}/annuler',[ConfirmationRdv::class,'annuler'])->name('confirmation.annuler');

    // Route::get('/api/reservations/{year}/{month}', [RendezVousController::class, 'index']);
});