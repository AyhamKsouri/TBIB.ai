<?php

namespace App\Http\Controllers;

use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AIController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function analyzeSymptoms(Request $request)
    {
        $request->validate([
            'symptoms' => 'required|string',
        ]);

        $result = $this->aiService->analyzeSymptoms($request->symptoms);

        return response()->json($result);
    }

    public function processAppointment(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'doctor_id' => 'nullable|integer',
            'specialty' => 'nullable|string',
        ]);

        $user = Auth::user();
        if (!$user->patient) {
            return response()->json(['message' => 'Only patients can use the AI assistant for appointments.'], 403);
        }

        $result = $this->aiService->processAppointment(
            $user->patient->id,
            $request->message,
            $request->doctor_id,
            $request->specialty
        );

        return response()->json($result);
    }
}
