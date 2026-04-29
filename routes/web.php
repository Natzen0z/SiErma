<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RiskController;
use App\Http\Controllers\AppContextController;
use App\Http\Controllers\AuditorController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AuditorMiddleware;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Risk management (for all authenticated users)
    Route::get('/', [RiskController::class, 'index'])->name('risk.index');
    Route::post('/risks', [RiskController::class, 'store'])->name('risk.store');
    Route::put('/risks/{risk}', [RiskController::class, 'update'])->name('risk.update');
    Route::delete('/risks/{risk}', [RiskController::class, 'destroy'])->name('risk.destroy');
    Route::get('/api/statistics', [RiskController::class, 'statistics'])->name('risk.statistics');

    // Admin routes
    Route::middleware(AdminMiddleware::class)->prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
        Route::get('/risks', [AdminController::class, 'risks'])->name('admin.risks');
        Route::get('/rekap-tahunan', [AdminController::class, 'annualRecap'])->name('admin.annual_recap');

        // Unit management
        Route::get('/units', [AdminController::class, 'units'])->name('admin.units');
        Route::post('/units', [AdminController::class, 'storeUnit'])->name('admin.units.store');
        Route::delete('/units/{unit}', [AdminController::class, 'destroyUnit'])->name('admin.units.destroy');

        // Sub-Unit management
        Route::get('/sub-units', [AdminController::class, 'subUnits'])->name('admin.sub_units');
        Route::post('/sub-units', [AdminController::class, 'storeSubUnit'])->name('admin.sub_units.store');
        Route::delete('/sub-units/{id}', [AdminController::class, 'destroySubUnit'])->name('admin.sub_units.destroy');

        Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
        Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('admin.categories.destroy');

        // Context management (Moved to shared authenticated routes to allow Wadir)
    });

    // Assessment route (shared - accessible to all authenticated users: unit admins submit self-assessments, auditors submit audit results)
    Route::post('/assessment', [AuditorController::class, 'storeAssessment'])->name('auditor.assessment.store');

    // Auditor routes (dashboard & print remain auditor-only)
    Route::middleware(AuditorMiddleware::class)->prefix('auditor')->group(function () {
        Route::get('/dashboard', [AuditorController::class, 'dashboard'])->name('auditor.dashboard');
        Route::get('/assessment/{id}/print', [AuditorController::class, 'printAssessment'])->name('auditor.assessment.print');
    });

    // Announcement routes
    Route::get('/announcements/active', [App\Http\Controllers\AnnouncementController::class, 'active'])->name('announcements.active');
    Route::post('/announcements', [App\Http\Controllers\AnnouncementController::class, 'store'])->name('announcements.store');
    Route::put('/announcements/{announcement}', [App\Http\Controllers\AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [App\Http\Controllers\AnnouncementController::class, 'destroy'])->name('announcements.destroy');

    // Publicly accessible context for all authenticated users
    Route::get('/context/{year}', [AppContextController::class, 'show'])->name('context.show');
    Route::post('/context', [AppContextController::class, 'store'])->name('risk.context.store');
});
