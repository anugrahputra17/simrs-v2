<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\ClinicalController;
use App\Http\Controllers\TerminologyProxyController;
use App\Http\Controllers\HybridTrackerController;
use App\Http\Controllers\BiostatisticController;
use App\Http\Controllers\AuditTrailController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirect root to login
Route::get('/', fn() => redirect('/login'));

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admission Desk
    Route::get('/admission/search', [AdmissionController::class, 'searchPatient'])->name('admission.search');
    Route::get('/admission', [AdmissionController::class, 'index'])->name('admission.index');
    Route::post('/admission', [AdmissionController::class, 'store'])->name('admission.store');

    // Clinical Workstation
    Route::get('/clinical', [ClinicalController::class, 'index'])->name('clinical.index');
    Route::get('/clinical/patient/{registrationId}', [ClinicalController::class, 'getPatientData'])->name('clinical.patient');
    Route::post('/clinical', [ClinicalController::class, 'store'])->name('clinical.store');

    // Coding (Terminology)
    Route::get('/coding', [TerminologyProxyController::class, 'list'])->name('coding.list');
    Route::get('/coding/{medicalRecordId}', [TerminologyProxyController::class, 'index'])->name('coding.index');
    Route::post('/coding/{medicalRecordId}/complete', [TerminologyProxyController::class, 'completeCoding'])->name('coding.complete');
    Route::get('/api/snomed/search', [TerminologyProxyController::class, 'search'])->name('snomed.search');
    Route::get('/api/snomed/map/{conceptId}', [TerminologyProxyController::class, 'mapIcd10'])->name('snomed.map');
    Route::post('/api/coding/store', [TerminologyProxyController::class, 'storeCoding'])->name('coding.store');
    Route::delete('/api/coding/{id}', [TerminologyProxyController::class, 'deleteCoding'])->name('coding.delete');

    // Hybrid Tracker
    Route::get('/hybrid-tracker', [HybridTrackerController::class, 'index'])->name('hybrid-tracker.index');
    Route::put('/hybrid-tracker/{id}', [HybridTrackerController::class, 'update'])->name('hybrid-tracker.update');

    // Biostatistics Dashboard
    Route::get('/biostatistic', [BiostatisticController::class, 'index'])->name('biostatistic.index');

    // Audit Trail
    Route::get('/audit-trail', [AuditTrailController::class, 'index'])->name('audit-trail.index');
});
