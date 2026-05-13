<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\ExportController; // We will use this later for the secure export

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| These routes define the web interface of your application.
|
*/

// --- PUBLIC-FACING ROUTES (The one-page site) ---

// 1. Home Page / Form Display
// When the user visits nigeriamortgages.com/
Route::get('/', [ApplicantController::class, 'showForm'])->name('application.form');

// 2. Form Submission (POST Request)
// When the user clicks the "Submit" button
Route::post('/submit', [ApplicantController::class, 'submitApplication'])->name('application.submit');

// 3. Success / Thank You Page
// The page the user is redirected to after a successful application
Route::get('/success', [ApplicantController::class, 'showSuccess'])->name('application.success');


// --- SECURE ADMIN ROUTES (For you to manage data) ---

// This will be a password-protected area for the dashboard and export functions.
// We will add the actual security layer (e.g., authentication) later.
Route::prefix('admin')->group(function () {

    // Placeholder for the secure dashboard view (showing the list of applicants)
    Route::get('/', [ExportController::class, 'showDashboard'])->name('admin.dashboard');

    // Button to download all data
    Route::get('/export', [ExportController::class, 'exportData'])->name('admin.export');
});

// Important Note: Remember to secure the '/admin' routes heavily later!
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
