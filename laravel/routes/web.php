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
use App\Http\Controllers\PaystackController; // For Paystack payment integration
use App\Http\Controllers\StaffPaymentController;
use App\Http\Controllers\StaffAssignmentController;
use App\Http\Controllers\StaffProfileController;
use App\Http\Controllers\StaffPaymentReportController;







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

// Paystack payment routes (outside of Staff role middleware since applicants will access these directly)
Route::post('/payment/{applicant}/paystack/initialize', [PaystackController::class, 'initialize'])
    ->name('paystack.initialize');

Route::get('/payment/paystack/callback', [PaystackController::class, 'callback'])
    ->name('paystack.callback');

Route::post('/payment/paystack/webhook', [PaystackController::class, 'webhook'])
    ->name('paystack.webhook');

// All staff routes will be protected by 'staff.auth' middleware to ensure only authenticated staff can access them
Route::middleware('staff.auth')->group(function () {

    // Common staff routes (accessible by all staff roles)
    Route::post('/staff/logout', [StaffAuthController::class, 'logout'])
        ->name('staff.logout');

    Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'])
        ->name('staff.dashboard');

    Route::patch('/staff/applications/{applicant}/status', [StaffApplicationController::class, 'updateStatus'])
        ->name('staff.applications.updateStatus');

    Route::get('/staff/applications/{applicant}', [StaffApplicantController::class, 'show'])
        ->name('staff.applications.show');

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
        ->name('staff.receipts.view')
        ->middleware('staff.role:admin,manager');

    Route::get('/staff/applications/{applicant}/documents/zip', [StaffDocumentZipController::class, 'download'])
        ->name('staff.applications.documents.zip');

    Route::patch('/staff/payments/{payment}/verify-manual', [StaffPaymentController::class, 'verifyManualPayment'])
        ->name('staff.payments.verifyManual')
        ->middleware('staff.role:admin,manager');
    
    Route::patch('/staff/applicants/{applicant}/assign', [StaffAssignmentController::class, 'assign'])
        ->name('staff.applicants.assign')
        ->middleware('staff.role:admin,manager');
    
    Route::get('/staff/profile', [StaffProfileController::class, 'edit'])
        ->name('staff.profile.edit');

    Route::patch('/staff/profile/password', [StaffProfileController::class, 'updatePassword'])
        ->name('staff.profile.password');

    Route::patch('/staff/profile/email', [StaffProfileController::class, 'updateEmail'])
        ->name('staff.profile.email');
    
    Route::get('/staff/payments', [StaffPaymentReportController::class, 'index'])
        ->name('staff.payments.index')
        ->middleware('staff.role:admin,manager');

    // Admin and Manager routes (only accessible by admin and manager roles); export and staff management
    Route::middleware('staff.role:admin,manager')->group(function () {

        Route::get('/staff/users', [StaffUserController::class, 'index'])
            ->name('staff.users.index');

        Route::post('/staff/users', [StaffUserController::class, 'store'])
            ->name('staff.users.store');

        Route::patch('/staff/users/{staffUser}/toggle-status', [StaffUserController::class, 'toggleStatus'])
            ->name('staff.users.toggleStatus');

        Route::get('/staff/applications/export/csv', [StaffExportController::class, 'exportCsv'])
            ->name('staff.applications.exportCsv');

    });

});

