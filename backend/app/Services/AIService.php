<?php
// backend/app/Services/AIService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.ai.url', 'http://localhost:8001');
    }

    /**
     * Main chat — used for the patient/doctor chatbot with Intent detection
     */
    public function chatWithIntent(string $message, string $context, string $role, array $history): array
    {
        try {
            $response = Http::timeout(60)->post("{$this->baseUrl}/ai/chat", [
                'message'   => $message,
                'context'   => $context,
                'user_role' => $role,
                'history'   => $history,
            ]);

            if ($response->successful()) {
                return $response->json(); // returns reply + intent 
            }

            return ['reply' => 'Sorry, the assistant is temporarily unavailable.', 'intent' => ['action' => 'none']];

        } catch (\Exception $e) {
            return ['reply' => 'Sorry, the assistant is temporarily unavailable.', 'intent' => ['action' => 'none']];
        }
    }

    /**
     * Main chat — used for the patient/doctor chatbot
     */
   public function chat(string $message, string $context = '', string $role = 'patient', array $history = []): string
{
    try {
        $response = Http::timeout(60)->post("{$this->baseUrl}/ai/chat", [
            'message'   => $message,
            'context'   => $context,
            'user_role' => $role,
            'history'   => $history,
        ]);

        return $response->successful()
            ? $response->json('reply')
            : 'Sorry, the assistant is temporarily unavailable.';

    } catch (\Exception $e) {
        return 'Sorry, the assistant is temporarily unavailable.';
    }
}

    /**
     * Symptom orientation for patients
     */
    public function analyzeSymptoms(string $symptoms): string
    {
        try {
            $response = Http::timeout(60)->post("{$this->baseUrl}/ai/analyze-symptoms", [
                'symptoms' => $symptoms,
            ]);

            return $response->successful()
                ? $response->json('orientation')
                : 'Unable to analyze symptoms at this time.';

        } catch (\Exception $e) {
            return 'Unable to analyze symptoms at this time.';
        }
    }

    /**
     * Report draft generation for doctors
     */
    public function generateReport(string $patientName, string $diagnosis, string $notes): string
    {
        try {
            $response = Http::timeout(60)->post("{$this->baseUrl}/ai/generate-report", [
                'patient_name' => $patientName,
                'diagnosis'    => $diagnosis,
                'notes'        => $notes,
            ]);

            return $response->successful()
                ? $response->json('report_draft')
                : 'Unable to generate report at this time.';

        } catch (\Exception $e) {
            return 'Unable to generate report at this time.';
        }
    }

    /**
     * Summarize consultation notes for doctors
     */
    public function summarize(string $text): string
    {
        try {
            $response = Http::timeout(60)->post("{$this->baseUrl}/ai/summarize", [
                'text' => $text,
            ]);

            return $response->successful()
                ? $response->json('summary')
                : 'Unable to summarize at this time.';

        } catch (\Exception $e) {
            return 'Unable to summarize at this time.';
        }
    }

    /**
     * Check if AI service is alive
     */
    public function isAvailable(): bool
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/health");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}