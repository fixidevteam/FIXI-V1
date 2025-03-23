<?php

use App\Http\Controllers\Admin\AdminGestionCalendrierController;
use App\Http\Controllers\Admin\AdminGestionCategorieController;
use App\Http\Controllers\Admin\AdminGestionDomaineController;
use App\Http\Controllers\Admin\AdminGestionGarageController;
use App\Http\Controllers\Admin\AdminGestionMarqueContoller;
use App\Http\Controllers\Admin\AdminGestionMechanicController;
use App\Http\Controllers\Admin\AdminGestionModeleController;
use App\Http\Controllers\Admin\AdminGestionOperationController;
use App\Http\Controllers\Admin\AdminGestionPapierPersoController;
use App\Http\Controllers\Admin\AdminGestionPapierVoitureController;
use App\Http\Controllers\Admin\AdminGestionQuartierController;
use App\Http\Controllers\Admin\AdminGestionSousOperationController;
use App\Http\Controllers\Admin\AdminGestionUserController;
use App\Http\Controllers\Admin\AdminGestionVilleController;
use App\Http\Controllers\Admin\AdminGestionPromotionsController;
use App\Http\Controllers\Admin\AdminGestionReservationsController;
use App\Http\Controllers\Admin\AdminGestionServiceController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use App\Models\Admin;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->prefix('fp-admin')->name('admin.')->group(function () {
    // Route::get('register', [RegisteredUserController::class, 'create'])
    //     ->name('register');

    // Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth:admin')->prefix('fp-admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // route admin : 
    Route::resource('/gestionUtilisateurs', AdminGestionUserController::class);
    // active :
    Route::post('/users/{id}/toggle-status', [AdminGestionUserController::class, 'toggleStatus'])->name('users.toggleStatus');
    Route::resource('/gestionGaragistes', AdminGestionMechanicController::class);
    // active :
    Route::post('/garagiste/{id}/toggle-status', [AdminGestionMechanicController::class, 'toggleStatus'])->name('garagiste.toggleStatus');
    Route::resource('/gestionGarages', AdminGestionGarageController::class);
    Route::resource('/gestionPapierPerso', AdminGestionPapierPersoController::class);
    Route::resource('/gestionPapierVoiture', AdminGestionPapierVoitureController::class);
    Route::resource('/gestionOperation', AdminGestionOperationController::class);
    Route::resource('/gestionSousOperation', AdminGestionSousOperationController::class);
    Route::resource('/gestionCategorie', AdminGestionCategorieController::class);
    Route::resource('/gestionMarque', AdminGestionMarqueContoller::class);
    Route::resource('/gestionModele', AdminGestionModeleController::class);
    Route::resource('/gestionVille', AdminGestionVilleController::class);
    Route::resource('/gestionQuartier', AdminGestionQuartierController::class);
    Route::resource('/gestionDomaine', AdminGestionDomaineController::class);
    Route::resource('/gestionService', AdminGestionServiceController::class);
    Route::resource('/gestionPromotions', AdminGestionPromotionsController::class);
    Route::resource('/gestionReservations', AdminGestionReservationsController::class);
    Route::patch('/gestionReservations/{id}/update-status', [AdminGestionReservationsController::class, 'updateStatus'])
        ->name('reservations.updateStatus');
    Route::get('/gestionCalendrier', [AdminGestionCalendrierController::class, 'index'])->name('gestionCalendrier.index');
    Route::get('/gestionCalendrier/{id}', [AdminGestionCalendrierController::class, 'show'])->name('gestionCalendrier.show');
    Route::get('/gestionCalendrier/{id}/create', [AdminGestionCalendrierController::class, 'create'])->name('gestionCalendrier.create');
    Route::post('/gestionCalendrier/{id}/store', [AdminGestionCalendrierController::class, 'store'])->name('gestionCalendrier.store');
    Route::get('/gestionCalendrier/{id}/edit', [AdminGestionCalendrierController::class, 'edit'])->name('gestionCalendrier.edit');
    Route::patch('/gestionCalendrier/update/{id}', [AdminGestionCalendrierController::class, 'update'])->name('gestionCalendrier.update');
    Route::delete('/gestionCalendrier/destroy/{id}', [AdminGestionCalendrierController::class, 'destroy'])->name('gestionCalendrier.destroy');



    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});