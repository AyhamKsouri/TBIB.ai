@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Créer un rapport médical</h2>
        <p class="text-gray-600 mb-8">Patient: <span class="font-bold text-blue-600">{{ $patient->user->name }}</span> ({{ $patient->age }} ans, {{ $patient->gender }})</p>

        <form action="{{ route('doctor.reports.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="patient_id" value="{{ $patient->id }}">
            <input type="hidden" name="appointment_id" value="{{ $appointment_id }}">

            <div>
                <label class="block text-sm font-medium text-gray-700">Diagnostic</label>
                <textarea name="diagnosis" required rows="4" class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Saisissez le diagnostic..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Ordonnance / Prescription</label>
                <textarea name="prescription" required rows="4" class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Liste des médicaments et posologie..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Notes (Optionnel)</label>
                <textarea name="notes" rows="2" class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Notes additionnelles..."></textarea>
            </div>

            <div class="pt-4 flex space-x-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-md font-bold hover:bg-blue-700 transition duration-300">
                    Enregistrer le rapport
                </button>
                <a href="{{ route('doctor.appointments.index') }}" class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-md font-bold text-center hover:bg-gray-200 transition duration-300">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
