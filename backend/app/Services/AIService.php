<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.ai.url', 'http://localhost:8001');
    }

    public function analyzeSymptoms(string $symptoms)
    {
        try {
            $response = Http::post("{$this->baseUrl}/ai/analyze-symptoms", [
                'symptoms' => $symptoms
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error("AI Service Error: " . $e->getMessage());
            return ['error' => 'Could not connect to AI service'];
        }
    }

    public function processAppointment(int $patientId, string $message, ?int $doctorId = null, ?string $specialty = null)
    {
        try {
            $response = Http::post("{$this->baseUrl}/ai/process-appointment", [
                'patient_id' => $patientId,
                'message' => $message,
                'doctor_id' => $doctorId,
                'specialty' => $specialty
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error("AI Service Error: " . $e->getMessage());
            return ['error' => 'Could not connect to AI service'];
        }
    }
}
