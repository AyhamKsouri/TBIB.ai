<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIChatController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/departments', [DoctorController::class, 'departments']);
Route::get('/doctors', [DoctorController::class, 'index']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Appointment routes
    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::post('/appointments', [AppointmentController::class, 'store'])->middleware('role:patient,admin');
    Route::patch('/appointments/{appointment}', [AppointmentController::class, 'update']);
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy']);
    
    // Doctor specific routes
    Route::get('/doctors/{doctor}', [DoctorController::class, 'show']);
    
    // Report routes
    Route::get('/reports', [ReportController::class, 'index']);
    Route::post('/reports', [ReportController::class, 'store'])->middleware('role:doctor,admin');
    Route::get('/reports/{report}', [ReportController::class, 'show']);
    
    // AI routes
    Route::post('/ai/analyze-symptoms', [AIController::class, 'analyzeSymptoms']);
    Route::post('/ai/process-appointment', [AIController::class, 'processAppointment']);
    Route::post('/ai/chat', [AIChatController::class, 'chat']);
});
