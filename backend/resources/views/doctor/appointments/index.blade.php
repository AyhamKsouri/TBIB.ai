@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Mon Agenda</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Heure</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mriv</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($appointments as $appointment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $appointment->date }}</div>
                                <div class="text-sm text-gray-500">{{ $appointment->time }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $appointment->patient->user->name }}</div>
                                <div class="text-sm text-gray-500 mb-2">Âge: {{ $appointment->patient->age }} ans</div>
                                
                                <!-- Detailed Medical Info for Doctor -->
                                <div class="space-y-1">
                                    @if($appointment->patient->persistent_sickness)
                                        <div class="text-[11px] leading-tight">
                                            <span class="font-bold text-red-700 uppercase">🏥 Pathologie :</span>
                                            <span class="text-gray-600">{{ $appointment->patient->persistent_sickness }}</span>
                                        </div>
                                    @endif
                                    @if($appointment->patient->allergies)
                                        <div class="text-[11px] leading-tight">
                                            <span class="font-bold text-orange-700 uppercase">⚠️ Allergies :</span>
                                            <span class="text-gray-600">{{ $appointment->patient->allergies }}</span>
                                        </div>
                                    @endif
                                    @if($appointment->patient->current_treatments)
                                        <div class="text-[11px] leading-tight">
                                            <span class="font-bold text-purple-700 uppercase">💊 Traitements :</span>
                                            <span class="text-gray-600">{{ $appointment->patient->current_treatments }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $appointment->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $appointment->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $appointment->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                @if($appointment->status === 'scheduled')
                                    @if($appointment->date <= date('Y-m-d'))
                                        <a href="{{ route('doctor.reports.create', ['patient' => $appointment->patient_id, 'appointment_id' => $appointment->id]) }}" class="text-blue-600 hover:text-blue-900">Terminer & Rapport</a>
                                    @else
                                        <span class="text-gray-400 cursor-not-allowed" title="Disponible uniquement le jour du rendez-vous">Terminer & Rapport</span>
                                    @endif
                                    
                                    <button onclick="openReproModal({{ $appointment->id }}, '{{ $appointment->patient->user->name }}')" class="text-orange-600 hover:text-orange-900">Reprogrammer</button>

                                    <form action="{{ route('doctor.appointments.update', $appointment->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="text-red-600 hover:text-red-900">Annuler</button>
                                    </form>
                                @elseif($appointment->status === 'pending_repro')
                                    <span class="text-orange-500 font-medium italic">En attente du mriv</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Aucun rendez-vous prévu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Repro Modal -->
<div id="reproModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-bold text-gray-900">Reprogrammer le RDV</h3>
            <p class="text-sm text-gray-500 mt-2" id="reproPatientName"></p>
            <form id="reproForm" method="POST" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nouvelle Date</label>
                    <input type="date" name="suggested_date" required min="{{ date('Y-m-d') }}" class="w-full border rounded-md px-3 py-2">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nouvelle Heure</label>
                    <select name="suggested_time" required class="w-full border rounded-md px-3 py-2">
                        @for($i = 8; $i <= 17; $i++)
                            <option value="{{ sprintf('%02d:00', $i) }}">{{ sprintf('%02d:00', $i) }}</option>
                            <option value="{{ sprintf('%02d:30', $i) }}">{{ sprintf('%02d:30', $i) }}</option>
                        @endfor
                    </select>
                </div>
                <div class="flex space-x-3">
                    <button type="submit" class="flex-1 bg-orange-600 text-white py-2 rounded-md hover:bg-orange-700 font-bold">Suggérer</button>
                    <button type="button" onclick="closeReproModal()" class="flex-1 bg-gray-100 text-gray-700 py-2 rounded-md hover:bg-gray-200">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openReproModal(appointmentId, patientName) {
        const form = document.getElementById('reproForm');
        form.action = `/doctor/appointments/${appointmentId}/suggest-timing`;
        document.getElementById('reproPatientName').textContent = 'Mriv: ' + patientName;
        document.getElementById('reproModal').classList.remove('hidden');
    }

    function closeReproModal() {
        document.getElementById('reproModal').classList.add('hidden');
    }

    window.onclick = function(event) {
        const modal = document.getElementById('reproModal');
        if (event.target == modal) {
            closeReproModal();
        }
    }
</script>
@endsection
