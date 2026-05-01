<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function indexWeb(Request $request)
    {
        $query = Doctor::with(['user', 'department', 'feedbacks.patient.user']);

        if ($request->has('specialty')) {
            $query->where('specialty', 'like', '%' . $request->specialty . '%');
        }

        if ($request->has('department_id') && $request->department_id != "") {
            $query->where('department_id', $request->department_id);
        }

        $doctors = $query->get();
        $departments = Department::all();

        return view('patient.doctors.index', compact('doctors', 'departments'));
    }

    public function index(Request $request)
    {
        $query = Doctor::with(['user', 'department']);

        if ($request->has('specialty')) {
            $query->where('specialty', 'like', '%' . $request->specialty . '%');
        }

        if ($request->has('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        return response()->json($query->get());
    }

    public function show(Doctor $doctor)
    {
        return response()->json($doctor->load(['user', 'department', 'appointments']));
    }

    public function showWeb(Doctor $doctor)
    {
        $doctor->load(['user', 'department', 'feedbacks.patient.user']);
        return view('patient.doctors.show', compact('doctor'));
    }

    public function departments()
    {
        return response()->json(Department::all());
    }

    public function patientsWeb()
    {
        $user = Auth::user();
        if (!$user->isDoctor()) {
            return redirect('/dashboard');
        }

        // Get unique patients who have appointments with this doctor
        $patients = \App\Models\Patient::whereHas('appointments', function ($query) use ($user) {
            $query->where('doctor_id', $user->doctor->id);
        })->with('user')->get();

        return view('doctor.patients.index', compact('patients'));
    }

    public function profile()
    {
        $doctor = Auth::user()->doctor->load('department');
        $departments = Department::all();
        return view('doctor.profile', compact('doctor', 'departments'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'specialty' => 'required|string',
            'experience_years' => 'required|integer',
            'phone' => 'nullable|string',
            'location' => 'nullable|string',
            'work_days' => 'nullable|string',
            'work_hours' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
        ]);

        $doctor = Auth::user()->doctor;
        $doctor->update($request->all());

        return back()->with('success', 'Profil mis à jour avec succès.');
    }
}
