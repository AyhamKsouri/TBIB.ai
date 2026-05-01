<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function indexWeb()
    {
        $user = Auth::user();
        $query = Report::with(['doctor.user', 'patient.user']);

        if ($user->role === 'patient') {
            $query->where('patient_id', $user->patient->id);
            $view = 'patient.reports.index';
        } elseif ($user->role === 'doctor') {
            $query->where('doctor_id', $user->doctor->id);
            $view = 'doctor.reports.index';
        } else {
            $view = 'dashboard';
        }

        $reports = $query->latest()->get();

        return view($view, compact('reports'));
    }

    public function create(\App\Models\Patient $patient)
    {
        $appointment_id = request('appointment_id');
        return view('doctor.reports.create', compact('patient', 'appointment_id'));
    }

    public function storeWeb(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'diagnosis' => 'required|string',
            'prescription' => 'required|string',
            'notes' => 'nullable|string',
            'appointment_id' => 'nullable|exists:appointments,id',
        ]);

        $user = Auth::user();
        
        Report::create([
            'doctor_id' => $user->doctor->id,
            'patient_id' => $request->patient_id,
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'notes' => $request->notes,
        ]);

        // Mark appointment as completed if ID is provided
        if ($request->appointment_id) {
            \App\Models\Appointment::where('id', $request->appointment_id)
                ->where('doctor_id', $user->doctor->id)
                ->update(['status' => 'completed']);
        }

        // Audit Log
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'created_report_web',
            'target_table' => 'reports',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('doctor.appointments.index')->with('success', 'Rapport médical créé et rendez-vous terminé.');
    }

    public function index()
    {
        $user = Auth::user();
        $query = Report::with(['doctor.user', 'patient.user']);

        if ($user->role === 'patient') {
            $query->where('patient_id', $user->patient->id);
        } elseif ($user->role === 'doctor') {
            $query->where('doctor_id', $user->doctor->id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'diagnosis' => 'required|string',
            'prescription' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        if ($user->role !== 'doctor' && $user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $doctorId = $user->role === 'doctor' ? $user->doctor->id : $request->doctor_id;

        if (!$doctorId) {
            return response()->json(['message' => 'Doctor ID is required.'], 400);
        }

        $report = Report::create([
            'doctor_id' => $doctorId,
            'patient_id' => $request->patient_id,
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'notes' => $request->notes,
        ]);

        // Audit Log
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'created_report',
            'target_table' => 'reports',
            'ip_address' => $request->ip(),
        ]);

        return response()->json($report, 201);
    }

    public function show(Report $report)
    {
        $user = Auth::user();
        
        // Authorization check
        if ($user->role === 'patient' && $report->patient_id !== $user->patient->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }
        if ($user->role === 'doctor' && $report->doctor_id !== $user->doctor->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return response()->json($report->load(['doctor.user', 'patient.user']));
    }
}
