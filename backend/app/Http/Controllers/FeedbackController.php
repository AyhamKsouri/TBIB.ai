<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Doctor;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'comment' => 'required|string|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $user = Auth::user();
        
        if (!$user->isPatient()) {
            return back()->with('error', 'Seuls les patients peuvent laisser des avis.');
        }

        // Check if feedback already exists for this doctor/patient to avoid spam
        $exists = Feedback::where('doctor_id', $request->doctor_id)
            ->where('patient_id', $user->patient->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Vous avez déjà laissé un avis pour ce médecin.');
        }

        Feedback::create([
            'doctor_id' => $request->doctor_id,
            'patient_id' => $user->patient->id,
            'comment' => $request->comment,
            'rating' => $request->rating,
        ]);

        // Audit Log
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'created_feedback',
            'target_table' => 'feedbacks',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Merci pour votre avis !');
    }
}
