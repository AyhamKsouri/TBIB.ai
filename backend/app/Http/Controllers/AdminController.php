<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_patients' => Patient::count(),
            'total_doctors' => Doctor::count(),
            'total_appointments' => Appointment::count(),
            'appointments_by_status' => Appointment::selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status'),
        ];

        $recent_logs = AuditLog::with('user')->orderBy('timestamp', 'desc')->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recent_logs'));
    }

    public function doctors()
    {
        $doctors = Doctor::with(['user', 'department'])->get();
        return view('admin.doctors.index', compact('doctors'));
    }

    public function patients()
    {
        $patients = Patient::with('user')->get();
        return view('admin.patients.index', compact('patients'));
    }

    public function auditLogs(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->orderBy('timestamp', 'desc')->paginate(20)->withQueryString();
        $users = User::orderBy('name')->get();

        return view('admin.audit.index', compact('logs', 'users'));
    }

    public function toggleUserStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', 'Statut de l\'utilisateur mis à jour.');
    }

    public function validateDoctor(Doctor $doctor)
    {
        $doctor->is_validated = true;
        $doctor->save();

        return back()->with('success', 'Compte médecin validé avec succès.');
    }

    public function feedbacks()
    {
        $feedbacks = \App\Models\Feedback::with(['doctor.user', 'patient.user'])->latest()->get();
        return view('admin.feedbacks.index', compact('feedbacks'));
    }

    public function deleteFeedback(\App\Models\Feedback $feedback)
    {
        $feedback->delete();
        return back()->with('success', 'Avis supprimé avec succès.');
    }
}
