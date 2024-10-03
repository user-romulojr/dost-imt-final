<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\HnrdaController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\SdgController;
use App\Http\Controllers\StrategicPillarController;
use App\Http\Controllers\ThematicAreaController;
use App\Http\Controllers\PrimaryIndicatorController;
use App\Http\Controllers\SecondaryIndicatorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/comment/{id}/store', [CommentController::class, 'store'])->name('comment.store');
    Route::put('/comment/{id}/update', [CommentController::class, 'update'])->name('comment.update');
    Route::delete('/comment/{id}/delete', [CommentController::class, 'destroy'])->name('comment.destroy');

    Route::get('/indicators/primary', [PrimaryIndicatorController::class, 'index'])->name('primaryIndicators.index');
    Route::get('/indicators/secondary', [SecondaryIndicatorController::class, 'index'])->name('secondaryIndicators.index');

    Route::middleware('submit')->group(function () {
        Route::get('/agency/indicators', [IndicatorController::class, 'agencyIndicators'])->name('agencyIndicators.index');
        Route::post('/agency/indicators', [IndicatorController::class, 'agencyIndicatorsStore'])->name('agencyIndicators.store');
        Route::put('/agency/indicators/{id}/update', [IndicatorController::class, 'agencyIndicatorsUpdate'])->name('agencyIndicators.update');
        Route::delete('/agency/indicators/{id}/delete', [IndicatorController::class, 'agencyIndicatorsDestroy'])->name('agencyIndicators.destroy');

        Route::post('/indicators/primary/{id}/store', [PrimaryIndicatorController::class, 'store'])->name('primaryIndicators.store');
        Route::put('/indicators/primary/{id}/update', [PrimaryIndicatorController::class, 'update'])->name('primaryIndicators.update');
        Route::delete('/indicators/primary/{id}/delete', [PrimaryIndicatorController::class, 'destroy'])->name('primaryIndicators.destroy');
        Route::post('/indicators/primary/select', [PrimaryIndicatorController::class, 'select'])->name('primaryIndicators.select');
        Route::post('/indicators/primary/submit', [PrimaryIndicatorController::class, 'submit'])->name('primaryIndicators.submit');
        Route::get('/indicators/primary/{id}/pending', [PrimaryIndicatorController::class, 'pending'])->name('primaryIndicators.pending');
        Route::get('/indicators/primary/approved', [PrimaryIndicatorController::class, 'approved'])->name('primaryIndicators.approved');

        Route::post('/indicators/secondary/{id}/store', [SecondaryIndicatorController::class, 'store'])->name('secondaryIndicators.store');
        Route::put('/indicators/secondary/{id}/update', [SecondaryIndicatorController::class, 'update'])->name('secondaryIndicators.update');
        Route::delete('/indicators/secondary/{id}/delete', [SecondaryIndicatorController::class, 'destroy'])->name('secondaryIndicators.destroy');
        Route::post('/indicators/secondary/select', [SecondaryIndicatorController::class, 'select'])->name('secondaryIndicators.select');
        Route::post('/indicators/secondary/submit', [SecondaryIndicatorController::class, 'submit'])->name('secondaryIndicators.submit');
        Route::get('/indicators/secondary/{id}/pending', [SecondaryIndicatorController::class, 'pending'])->name('secondaryIndicators.pending');
        Route::get('/indicators/secondary/approved', [SecondaryIndicatorController::class, 'approved'])->name('secondaryIndicators.approved');

    });

    Route::middleware('approve')->group(function () {
        Route::post('/indicators/primary/approve/{id}', [PrimaryIndicatorController::class, 'approve'])->name('primaryIndicators.approve');
        Route::post('/indicators/primary/disapprove/{id}', [PrimaryIndicatorController::class, 'disapprove'])->name('primaryIndicators.disapprove');
        Route::get('/indicators/primary/pending/admin', [PrimaryIndicatorController::class, 'pendingAdmin'])->name('primaryIndicators.pendingAdmin');
        Route::get('/indicators/primary/approved/admin', [PrimaryIndicatorController::class, 'approvedAdmin'])->name('primaryIndicators.approvedAdmin');

        Route::post('/indicators/secondary/approve/{id}', [SecondaryIndicatorController::class, 'approve'])->name('secondaryIndicators.approve');
        Route::post('/indicators/secondary/disapprove/{id}', [SecondaryIndicatorController::class, 'disapprove'])->name('secondaryIndicators.disapprove');
        Route::get('/indicators/secondary/pending/admin', [SecondaryIndicatorController::class, 'pendingAdmin'])->name('secondaryIndicators.pendingAdmin');
        Route::get('/indicators/secondary/approved/admin', [SecondaryIndicatorController::class, 'approvedAdmin'])->name('secondaryIndicators.approvedAdmin');
    });

    Route::middleware('library')->group(function () {
        Route::get('/indicators', [IndicatorController::class, 'index'])->name('indicators.index');
        Route::post('/indicators', [IndicatorController::class, 'store'])->name('indicators.store');
        Route::put('/indicators/{id}/update', [IndicatorController::class, 'update'])->name('indicators.update');
        Route::delete('/indicators/{id}/delete', [IndicatorController::class, 'destroy'])->name('indicators.destroy');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{id}/update', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}/delete', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/agencies', [AgencyController::class, 'index'])->name('agencies.index');
        Route::post('/agencies', [AgencyController::class, 'store'])->name('agencies.store');
        Route::put('/agencies/{id}/update', [AgencyController::class, 'update'])->name('agencies.update');
        Route::delete('/agencies/{id}/delete', [AgencyController::class, 'destroy'])->name('agencies.destroy');

        Route::get('/hnrdas', [HnrdaController::class, 'index'])->name('hnrdas.index');
        Route::post('/hnrdas', [HnrdaController::class, 'store'])->name('hnrdas.store');
        Route::put('/hnrdas/{id}/update', [HnrdaController::class, 'update'])->name('hnrdas.update');
        Route::delete('/hnrdas/{id}/delete', [HnrdaController::class, 'destroy'])->name('hnrdas.destroy');

        Route::get('/priorities', [PriorityController::class, 'index'])->name('priorities.index');
        Route::post('/priorities', [PriorityController::class, 'store'])->name('priorities.store');
        Route::put('/priorities/{id}/update', [PriorityController::class, 'update'])->name('priorities.update');
        Route::delete('/priorities/{id}/delete', [PriorityController::class, 'destroy'])->name('priorities.destroy');

        Route::get('/sdgs', [SdgController::class, 'index'])->name('sdgs.index');
        Route::post('/sdgs', [SdgController::class, 'store'])->name('sdgs.store');
        Route::put('/sdgs/{id}/update', [SdgController::class, 'update'])->name('sdgs.update');
        Route::delete('/sdgs/{id}/delete', [SdgController::class, 'destroy'])->name('sdgs.destroy');

        Route::get('/pillars', [StrategicPillarController::class, 'index'])->name('pillars.index');
        Route::post('/pillars', [StrategicPillarController::class, 'store'])->name('pillars.store');
        Route::put('/pillars/{id}/update', [StrategicPillarController::class, 'update'])->name('pillars.update');
        Route::delete('/pillars/{id}/delete', [StrategicPillarController::class, 'destroy'])->name('pillars.destroy');

        Route::get('/areas', [ThematicAreaController::class, 'index'])->name('areas.index');
        Route::post('/areas', [ThematicAreaController::class, 'store'])->name('areas.store');
        Route::put('/areas/{id}/update', [ThematicAreaController::class, 'update'])->name('areas.update');
        Route::delete('/areas/{id}/delete', [ThematicAreaController::class, 'destroy'])->name('areas.destroy');
    });

    Route::post('/store-indicators-dropdown-state', function (Illuminate\Http\Request $request) {
        session(['indicators_dropdown_open' => $request->input('open')]);
        return response()->json(['status' => 'success']);
    })->name('store.indicators.dropdown.state');

    Route::post('/store-library-dropdown-state', function (Illuminate\Http\Request $request) {
        session(['library_dropdown_open' => $request->input('open')]);
        return response()->json(['status' => 'success']);
    })->name('store.library.dropdown.state');
});

require __DIR__.'/auth.php';
