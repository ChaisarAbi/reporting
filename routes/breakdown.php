<?php

use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\OperatorController;
use Illuminate\Support\Facades\Route;

// Operator Routes
Route::middleware(['auth', 'role:leader_operator'])->prefix('operator')->name('operator.')->group(function () {
    Route::get('/dashboard', [OperatorController::class, 'dashboard'])->name('dashboard');
    Route::get('/reports', [OperatorController::class, 'reports'])->name('reports');
    Route::get('/reports/export/excel', [OperatorController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/reports/export/pdf', [OperatorController::class, 'exportPdf'])->name('reports.export.pdf');
    Route::get('/create', [OperatorController::class, 'create'])->name('create');
    Route::post('/create', [OperatorController::class, 'store'])->name('store');
    Route::get('/reports/{breakdownReport}', [OperatorController::class, 'show'])->name('show');
    Route::get('/reports/{breakdownReport}/export/pdf', [OperatorController::class, 'exportSinglePdf'])->name('reports.export.single.pdf');
});

// Maintenance Routes
Route::middleware(['auth', 'role:leader_teknisi'])->prefix('maintenance')->name('maintenance.')->group(function () {
    Route::get('/dashboard', [MaintenanceController::class, 'dashboard'])->name('dashboard');
    Route::get('/reports', [MaintenanceController::class, 'reports'])->name('reports');
    Route::get('/reports/export/excel', [MaintenanceController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/reports/export/pdf', [MaintenanceController::class, 'exportPdf'])->name('reports.export.pdf');
    Route::get('/reports/{breakdownReport}', [MaintenanceController::class, 'show'])->name('show');
    Route::get('/reports/{breakdownReport}/export/pdf', [MaintenanceController::class, 'exportSinglePdf'])->name('reports.export.single.pdf');
    Route::post('/reports/{breakdownReport}/start-repair', [MaintenanceController::class, 'startRepair'])->name('start-repair');
    Route::get('/reports/{breakdownReport}/complete', [MaintenanceController::class, 'showCompleteForm'])->name('show-complete-form');
    Route::post('/reports/{breakdownReport}/complete', [MaintenanceController::class, 'completeRepair'])->name('complete-repair');
    Route::get('/analytics', [MaintenanceController::class, 'analytics'])->name('analytics');
    Route::get('/analytics/filter', [MaintenanceController::class, 'filterAnalytics'])->name('analytics.filter');
    Route::get('/analytics/export/pdf', [MaintenanceController::class, 'exportAnalyticsPdf'])->name('analytics.export.pdf');
});

// Home route based on user role
Route::get('/home', function () {
    $user = auth()->user();
    
    if ($user->isLeaderOperator()) {
        return redirect()->route('operator.dashboard');
    } elseif ($user->isLeaderTeknisi()) {
        return redirect()->route('maintenance.dashboard');
    }
    
    return redirect('/');
})->middleware('auth')->name('home');
