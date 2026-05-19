<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\ExportController; // We will use this later for the secure export
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\StaffApplicationController;
use App\Http\Controllers\StaffApplicantController;
use App\Http\Controllers\StaffExportController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\ApplicantNoteController;
use App\Http\Controllers\ApplicantTaskController;
use App\Http\Controllers\StaffTaskController;
use App\Http\Controllers\StaffFileController;
use App\Http\Controllers\StaffDocumentZipController;
use App\Http\Controllers\StaffUserController;


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
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/apply', [ApplicantController::class, 'showForm'])
    ->name('application.form');

// 2. Form Submission (POST Request)
// When the user clicks the "Submit" button
Route::post('/submit', [ApplicantController::class, 'submitApplication'])->name('application.submit');

// 3. Success / Thank You Page
// The page the user is redirected to after a successful application
Route::get('/success', [ApplicantController::class, 'showSuccess'])->name('application.success');

// 4. Payment Page (Optional, if you want to handle payments)
Route::get('/payment/{id}', [ApplicantController::class, 'showPaymentPage'])
    ->name('application.payment');

// 5. Payment Receipt Submission (POST Request)
Route::post('/payment/{id}', [ApplicantController::class, 'submitPaymentReceipt'])
    ->name('application.payment.submit');


// --- SECURE ADMIN ROUTES (For you to manage data) ---

// This will be a password-protected area for the dashboard and export functions.
// We will add the actual security layer (e.g., authentication) later.


// Important Note: Remember to secure the '/admin' routes heavily later!
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/staff/login', [StaffAuthController::class, 'showLogin'])
    ->name('staff.login');

Route::post('/staff/login', [StaffAuthController::class, 'login'])
    ->name('staff.login.submit');

Route::middleware('staff.auth')->group(function () {

    // all staff routes here
    Route::post('/staff/logout', [StaffAuthController::class, 'logout'])
        ->name('staff.logout');

    Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'])
        ->name('staff.dashboard');

    Route::patch('/staff/applications/{applicant}/status', [StaffApplicationController::class, 'updateStatus'])
        ->name('staff.applications.updateStatus');

    Route::get('/staff/applications/{applicant}', [StaffApplicantController::class, 'show'])
        ->name('staff.applications.show');

    Route::get('/staff/applications/export/csv', [StaffExportController::class, 'exportCsv'])
        ->name('staff.applications.exportCsv');

    Route::post('/staff/applications/{applicant}/notes', [ApplicantNoteController::class, 'store'])
        ->name('staff.applications.notes.store');

    Route::post('/staff/applications/{applicant}/tasks', [ApplicantTaskController::class, 'store'])
        ->name('staff.applications.tasks.store');

    Route::patch('/staff/tasks/{task}/complete', [ApplicantTaskController::class, 'complete'])
        ->name('staff.tasks.complete');

    Route::get('/staff/tasks', [StaffTaskController::class, 'index'])
        ->name('staff.tasks.index');
        
    Route::get('/staff/documents/{document}/view', [StaffFileController::class, 'viewDocument'])
        ->name('staff.documents.view');

    Route::get('/staff/applications/{applicant}/receipt/view', [StaffFileController::class, 'viewReceipt'])
        ->name('staff.receipts.view');

    Route::get('/staff/applications/{applicant}/documents/zip', [StaffDocumentZipController::class, 'download'])
        ->name('staff.applications.documents.zip');
    
    Route::get('/staff/users', [StaffUserController::class, 'index'])
        ->name('staff.users.index');

    Route::post('/staff/users', [StaffUserController::class, 'store'])
        ->name('staff.users.store');

    Route::patch('/staff/users/{staffUser}/toggle-status', [StaffUserController::class, 'toggleStatus'])
        ->name('staff.users.toggleStatus');

});

