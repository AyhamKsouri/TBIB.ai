<?php
// backend/app/Http/Controllers/AIChatController.php

namespace App\Http\Controllers;

use App\Services\AIService;
use App\Models\Chat;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AIChatController extends Controller
{
    public function __construct(private AIService $ai) {}

    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $user    = auth()->user();
        $message = $request->input('message');

        // Load last 10 messages for conversation history
        $history = Chat::where('user_id', $user->id)
            ->latest()
            ->limit(10)
            ->get()
            ->reverse()
            ->map(fn($chat) => [
                ['role' => 'user',      'content' => $chat->message],
                ['role' => 'assistant', 'content' => $chat->response],
            ])
            ->flatten(1)
            ->values()
            ->toArray();

        $context  = $this->buildContext($user);
        $response = $this->ai->chatWithIntent($message, $context, $user->role, $history);

        $reply  = $response['reply'];
        $intent = $response['intent'] ?? ['action' => 'none'];

        // Execute the intent and get structured results
        $actionData = $this->executeIntent($intent, $user);
        
        $component = $actionData['component'] ?? null;
        $componentData = $actionData['component_data'] ?? null;
        
        if (isset($actionData['message'])) {
            $reply = $actionData['message']; // Override or append? Usually override if it's a specific success message
        }

        Chat::create([
            'user_id'  => $user->id,
            'message'  => $message,
            'response' => $reply,
        ]);

        return response()->json([
            'reply' => $reply, 
            'intent' => $intent,
            'component' => $component,
            'component_data' => $componentData,
            'buttons' => $intent['buttons'] ?? null
        ]);
    }

    private function executeIntent(array $intent, $user): array
    {
        $action = $intent['action'] ?? 'none';
        $result = ['message' => null, 'component' => null, 'component_data' => []];

        switch ($action) {
            // --- Patient Actions ---
            case 'book_appointment':
                if (!$user->patient) return $result;
                $doctorId = $intent['doctor_id'] ?? null;
                $date     = $intent['date'] ?? null;
                $time     = $intent['time'] ?? null;
                if (!$doctorId || !$date || !$time) {
                    $result['message'] = "Il me manque des détails (médecin, date ou heure) pour confirmer votre rendez-vous.";
                    return $result;
                }
                
                $exists = Appointment::where('doctor_id', $doctorId)->where('date', $date)->where('time', $time)->where('status', '!=', 'cancelled')->exists();
                if ($exists) {
                    $result['message'] = "Désolé, ce créneau est déjà pris. Souhaitez-vous une autre heure ?";
                    return $result;
                }

                $appt = Appointment::create(['patient_id' => $user->patient->id, 'doctor_id' => $doctorId, 'date' => $date, 'time' => $time, 'status' => 'pending', 'booked_via' => 'ai_assistant']);
                $doctor = Doctor::with('user')->find($doctorId);
                
                $result['message'] = "C'est fait ! Votre rendez-vous est enregistré.";
                $result['component'] = 'appointment_confirmation';
                $result['component_data'] = [
                    'doctor_name' => $doctor->user->name,
                    'specialty' => $doctor->specialty,
                    'date' => $appt->date,
                    'time' => $appt->time
                ];
                return $result;

            case 'cancel_appointment':
                $appointmentId = $intent['appointment_id'] ?? null;
                if (!$appointmentId) return $result;
                $appt = Appointment::where('id', $appointmentId)->where('patient_id', $user->patient->id ?? null)->first();
                if (!$appt) {
                    $result['message'] = "Je n'ai pas trouvé ce rendez-vous.";
                    return $result;
                }
                $appt->update(['status' => 'cancelled']);
                $result['message'] = "✅ Votre rendez-vous a été annulé avec succès.";
                return $result;

            case 'view_appointments':
                if (!$user->patient) return $result;
                $appointments = Appointment::where('patient_id', $user->patient->id)
                    ->where('date', '>=', today())
                    ->where('status', '!=', 'cancelled')
                    ->with('doctor.user')
                    ->orderBy('date')
                    ->get();
                
                $result['message'] = "Voici vos prochains rendez-vous :";
                $result['component'] = 'appointment_list';
                $result['component_data'] = [
                    'appointments' => $appointments->map(fn($a) => [
                        'doctor_name' => $a->doctor->user->name,
                        'specialty' => $a->doctor->specialty,
                        'date' => $a->date,
                        'time' => $a->time
                    ])
                ];
                return $result;

            // --- Doctor Actions ---
            case 'next_patient':
                if (!$user->doctor) return $result;
                $next = Appointment::where('doctor_id', $user->doctor->id)
                    ->where('date', today())
                    ->where('time', '>', now()->format('H:i'))
                    ->where('status', '!=', 'cancelled')
                    ->with('patient.user')
                    ->orderBy('time')
                    ->first();
                
                if (!$next) {
                    $result['message'] = "Vous n'avez plus de patients prévus pour aujourd'hui.";
                } else {
                    $result['message'] = "Votre prochain patient est **{$next->patient->user->name}** à **{$next->time}**.";
                }
                return $result;

            case 'view_patient_history':
                if (!$user->doctor) return $result;
                $patientId = $intent['patient_id'] ?? null;
                if (!$patientId) {
                    $result['message'] = "De quel patient souhaitez-vous consulter l'historique ?";
                    return $result;
                }
                
                $reports = \App\Models\Report::where('patient_id', $patientId)
                    ->where('doctor_id', $user->doctor->id)
                    ->latest()
                    ->limit(3)
                    ->get();
                
                if ($reports->isEmpty()) {
                    $result['message'] = "Aucun rapport de consultation précédent trouvé pour ce patient dans vos dossiers.";
                } else {
                    $history = "Dernières visites :\n";
                    foreach ($reports as $r) {
                        $history .= "- **{$r->created_at->format('d M Y')}** : {$r->diagnosis}\n";
                    }
                    $result['message'] = $history;
                }
                return $result;

            case 'save_report':
                if (!$user->doctor) return $result;
                $patientId = $intent['patient_id'] ?? null;
                $content = $intent['content'] ?? null;
                if (!$patientId || !$content) return $result;

                \App\Models\Report::create([
                    'doctor_id' => $user->doctor->id,
                    'patient_id' => $patientId,
                    'diagnosis' => $intent['diagnosis'] ?? 'Consultation',
                    'prescription' => $intent['prescription'] ?? 'N/A',
                    'notes' => $content
                ]);
                $result['message'] = "✅ Rapport enregistré dans le dossier médical du patient.";
                return $result;

            case 'summarize_notes':
                $text = $intent['text'] ?? null;
                if (!$text) return $result;
                $result['message'] = $this->ai->summarize($text);
                return $result;

            default:
                return $result;
        }
    }

    private function buildContext($user): string
    {
        $lines = [];
        $today = today()->toDateString();

        $lines[] = "Today's date: {$today}";
        $lines[] = "User: {$user->name}, Role: {$user->role}";

        if ($user->role === 'doctor' && $user->doctor) {
            $doctor = $user->doctor;

            // Today's appointments
            $todayAppts = Appointment::where('doctor_id', $doctor->id)
                ->where('date', $today)
                ->with('patient.user')
                ->orderBy('time')
                ->get();

            if ($todayAppts->isEmpty()) {
                $lines[] = "No appointments today.";
            } else {
                $lines[] = "Today's appointments:";
                foreach ($todayAppts as $appt) {
                    $lines[] = "- {$appt->time} — Patient: {$appt->patient->user->name} (ID:{$appt->patient->id}), Status: {$appt->status}";
                }
            }

            // Upcoming appointments this week
            $upcoming = Appointment::where('doctor_id', $doctor->id)
                ->whereBetween('date', [today(), today()->addDays(7)])
                ->with('patient.user')
                ->orderBy('date')->orderBy('time')
                ->get();

            $lines[] = "Upcoming appointments this week:";
            foreach ($upcoming as $appt) {
                $lines[] = "- {$appt->date} at {$appt->time} — {$appt->patient->user->name} (Patient ID:{$appt->patient->id})";
            }

            // All patients of this doctor
            $patients = \App\Models\Patient::whereHas('appointments', function($q) use ($doctor) {
                    $q->where('doctor_id', $doctor->id);
                })
                ->with(['user', 'appointments' => function($q) use ($doctor) {
                    $q->where('doctor_id', $doctor->id)->latest()->limit(1);
                }, 'reports' => function($q) use ($doctor) {
                    $q->where('doctor_id', $doctor->id)->latest()->limit(1);
                }])
                ->get();

            $lines[] = "Your patients:";
            foreach ($patients as $patient) {
                $lastAppt   = $patient->appointments->first();
                $lastReport = $patient->reports->first();

                $line = "- Patient ID:{$patient->id} — {$patient->user->name}, Age: {$patient->age}, Gender: {$patient->gender}";

                if ($lastAppt) {
                    $line .= ", Last visit: {$lastAppt->date}";
                }
                if ($lastReport) {
                    $line .= ", Last diagnosis: {$lastReport->diagnosis}";
                }

                $lines[] = $line;
            }

        } elseif ($user->role === 'patient' && $user->patient) {

            // Patient context (your existing logic)
            $appointments = Appointment::where('patient_id', $user->patient->id)
                ->where('date', '>=', today())
                ->with('doctor.user')
                ->orderBy('date')
                ->limit(5)
                ->get();

            if ($appointments->isEmpty()) {
                $lines[] = "Patient has no upcoming appointments.";
            } else {
                foreach ($appointments as $appt) {
                    $lines[] = "Appointment ID:{$appt->id} with Dr.{$appt->doctor->user->name} ({$appt->doctor->specialty}) on {$appt->date} at {$appt->time} — Status: {$appt->status}";
                }
            }

            // Available doctors
            $doctors = \App\Models\Doctor::with('user')->get();
            foreach ($doctors as $doc) {
                $lines[] = "Doctor ID:{$doc->id} — Dr.{$doc->user->name}, Specialty: {$doc->specialty}";
            }
        }

        return implode("\n", $lines);
    }
}
