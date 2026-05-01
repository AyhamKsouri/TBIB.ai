<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Doctor;
use App\Models\Department;

class AppointmentController extends Controller
{
    public function indexWeb()
    {
        $user = Auth::user();
        $query = Appointment::with(['patient.user', 'doctor.user']);

        if ($user->role === 'patient') {
            $query->where('patient_id', $user->patient->id);
            $view = 'patient.appointments.index';
        } elseif ($user->role === 'doctor') {
            $query->where('doctor_id', $user->doctor->id);
            $view = 'doctor.appointments.index';
        } else {
            $view = 'dashboard';
        }

        $appointments = $query->orderBy('date', 'desc')->orderBy('time', 'desc')->get();

        return view($view, compact('appointments'));
    }

    public function create()
    {
        $doctors = Doctor::with(['user', 'department'])->get();
        $departments = Department::all();
        return view('patient.appointments.create', compact('doctors', 'departments'));
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Appointment::with(['patient.user', 'doctor.user']);

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
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'booked_via' => 'string|in:manual,ai_assistant',
        ]);

        $user = Auth::user();
        
        if ($user->role !== 'patient' && $user->role !== 'admin') {
            return response()->json(['message' => 'Only patients or admins can book appointments.'], 403);
        }

        $patientId = $user->role === 'patient' ? $user->patient->id : $request->patient_id;

        if (!$patientId) {
            return response()->json(['message' => 'Patient ID is required.'], 400);
        }

        // Check for conflicting appointments
        $conflict = Appointment::where('doctor_id', $request->doctor_id)
            ->where('date', $request->date)
            ->where('time', $request->time)
            ->where('status', 'scheduled')
            ->exists();

        if ($conflict) {
            return response()->json(['message' => 'This slot is already booked.'], 422);
        }

        $appointment = Appointment::create([
            'patient_id' => $patientId,
            'doctor_id' => $request->doctor_id,
            'date' => $request->date,
            'time' => $request->time,
            'status' => 'scheduled',
            'booked_via' => $request->booked_via ?? 'manual',
        ]);

        // Audit Log
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'created_appointment',
            'target_table' => 'appointments',
            'ip_address' => $request->ip(),
        ]);

        if ($request->expectsJson()) {
            return response()->json($appointment, 201);
        }

        return redirect()->route('patient.appointments.index')->with('success', 'Rendez-vous confirmé !');
    }

    public function update(Request $request, Appointment $appointment)
    {
        $user = Auth::user();
        
        // Authorization check
        if ($user->role === 'patient' && $appointment->patient_id !== $user->patient->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }
        if ($user->role === 'doctor' && $appointment->doctor_id !== $user->doctor->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'status' => 'string|in:scheduled,cancelled,completed',
            'date' => 'date|after_or_equal:today',
            'time' => 'string',
        ]);

        $appointment->update($request->only(['status', 'date', 'time']));

        // Audit Log
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'updated_appointment',
            'target_table' => 'appointments',
            'ip_address' => $request->ip(),
        ]);

        if ($request->expectsJson()) {
            return response()->json($appointment);
        }

        return back()->with('success', 'Rendez-vous mis à jour.');
    }

    public function destroy(Appointment $appointment)
    {
        $user = Auth::user();
        
        if ($user->role === 'patient' && $appointment->patient_id !== $user->patient->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $appointment->update(['status' => 'cancelled']);

        // Audit Log
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'cancelled_appointment',
            'target_table' => 'appointments',
            'ip_address' => request()->ip(),
        ]);

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Appointment cancelled successfully.']);
        }
        
        return back()->with('success', 'Rendez-vous annulé.');
    }

    public function suggestNewTiming(Request $request, Appointment $appointment)
    {
        $request->validate([
            'suggested_date' => 'required|date|after_or_equal:today',
            'suggested_time' => 'required',
        ]);

        $user = Auth::user();
        if (!$user->isDoctor() || $appointment->doctor_id !== $user->doctor->id) {
            return back()->with('error', 'Action non autorisée.');
        }

        $appointment->update([
            'status' => 'pending_repro',
            'suggested_date' => $request->suggested_date,
            'suggested_time' => $request->suggested_time,
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'suggested_new_timing',
            'target_table' => 'appointments',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Nouvel horaire suggéré au patient.');
    }

    public function handleRepro(Request $request, Appointment $appointment)
    {
        $request->validate([
            'action' => 'required|in:accept,reject',
        ]);

        $user = Auth::user();
        if (!$user->isPatient() || $appointment->patient_id !== $user->patient->id) {
            return back()->with('error', 'Action non autorisée.');
        }

        if ($request->action === 'accept') {
            $appointment->update([
                'status' => 'scheduled',
                'date' => $appointment->suggested_date,
                'time' => $appointment->suggested_time,
                'suggested_date' => null,
                'suggested_time' => null,
            ]);
            $msg = 'Nouvel horaire accepté.';
        } else {
            $appointment->update([
                'status' => 'cancelled',
                'suggested_date' => null,
                'suggested_time' => null,
            ]);
            $msg = 'Rendez-vous annulé suite au refus de l\'horaire suggéré.';
        }

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'handled_repro_' . $request->action,
            'target_table' => 'appointments',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', $msg);
    }
}
