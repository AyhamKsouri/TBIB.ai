<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FeedbackController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'registerWeb']);

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
    ]);
});

Route::middleware(['auth', 'account_status'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Patient Routes
    Route::middleware(['role:patient'])->group(function () {
        Route::get('/patient/appointments/create', [AppointmentController::class, 'create'])->name('patient.appointments.create');
        Route::post('/patient/appointments', [AppointmentController::class, 'store'])->name('patient.appointments.store');
        Route::delete('/patient/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('patient.appointments.destroy');
        Route::post('/patient/appointments/{appointment}/handle-repro', [AppointmentController::class, 'handleRepro'])->name('patient.appointments.handle_repro');
        Route::get('/patient/appointments', [AppointmentController::class, 'indexWeb'])->name('patient.appointments.index');
        Route::get('/patient/reports', [ReportController::class, 'indexWeb'])->name('patient.reports.index');
        Route::post('/patient/feedback', [FeedbackController::class, 'store'])->name('patient.feedback.store');

        // AI Chat Proxy for Web
        Route::post('/ai/analyze-symptoms', [\App\Http\Controllers\AIController::class, 'analyzeSymptoms']);
        Route::post('/ai/process-appointment', [\App\Http\Controllers\AIController::class, 'processAppointment']);
    });

    // Doctor Routes
    Route::middleware(['role:doctor'])->group(function () {
        Route::get('/doctor/appointments', [AppointmentController::class, 'indexWeb'])->name('doctor.appointments.index');
        Route::patch('/doctor/appointments/{appointment}', [AppointmentController::class, 'update'])->name('doctor.appointments.update');
        Route::post('/doctor/appointments/{appointment}/suggest-timing', [AppointmentController::class, 'suggestNewTiming'])->name('doctor.appointments.suggest_timing');
        Route::get('/doctor/patients', [DoctorController::class, 'patientsWeb'])->name('doctor.patients.index');
        Route::get('/doctor/reports/create/{patient}', [ReportController::class, 'create'])->name('doctor.reports.create');
        Route::post('/doctor/reports', [ReportController::class, 'storeWeb'])->name('doctor.reports.store');
        Route::get('/doctor/profile', [DoctorController::class, 'profile'])->name('doctor.profile');
        Route::post('/doctor/profile', [DoctorController::class, 'updateProfile'])->name('doctor.profile.update');
    });

    // Public/Shared Routes for Authenticated Users
    Route::get('/reservez-un-rendez-vous', [DoctorController::class, 'indexWeb'])->name('public.doctors.index');
    Route::get('/tbib/{doctor}', [DoctorController::class, 'showWeb'])->name('public.doctors.show');

    // Admin Routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/doctors', [AdminController::class, 'doctors'])->name('admin.doctors.index');
        Route::get('/admin/patients', [AdminController::class, 'patients'])->name('admin.patients.index');
        Route::get('/admin/audit', [AdminController::class, 'auditLogs'])->name('admin.audit.index');
        Route::get('/admin/feedbacks', [AdminController::class, 'feedbacks'])->name('admin.feedbacks.index');
        Route::post('/admin/users/{user}/toggle', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle');
        Route::post('/admin/doctors/{doctor}/validate', [AdminController::class, 'validateDoctor'])->name('admin.doctors.validate');
        Route::delete('/admin/feedbacks/{feedback}', [AdminController::class, 'deleteFeedback'])->name('admin.feedbacks.delete');
    });

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    });
});
