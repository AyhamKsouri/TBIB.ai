@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Mes Mrivs</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($patients as $patient)
                <div class="border rounded-lg p-6 bg-gray-50">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="bg-blue-600 text-white rounded-full h-12 w-12 flex items-center justify-center font-bold text-xl">
                            {{ strtoupper(substr($patient->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">{{ $patient->user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $patient->age }} ans | {{ $patient->gender }}</p>
                        </div>
                    </div>

                    <!-- Medical Details -->
                    <div class="mb-4 space-y-2 text-sm border-t pt-4">
                        <div>
                            <span class="font-bold text-red-700 uppercase text-[10px]">Pathologies :</span>
                            <p class="text-gray-700 bg-red-50 p-2 rounded mt-1 border border-red-100">
                                {{ $patient->persistent_sickness ?? 'Aucune' }}
                            </p>
                        </div>
                        <div>
                            <span class="font-bold text-orange-700 uppercase text-[10px]">Allergies :</span>
                            <p class="text-gray-700 bg-orange-50 p-2 rounded mt-1 border border-orange-100">
                                {{ $patient->allergies ?? 'Aucune' }}
                            </p>
                        </div>
                        <div>
                            <span class="font-bold text-purple-700 uppercase text-[10px]">Traitements :</span>
                            <p class="text-gray-700 bg-purple-50 p-2 rounded mt-1 border border-purple-100">
                                {{ $patient->current_treatments ?? 'Aucun' }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <a href="{{ route('doctor.reports.create', $patient->id) }}" class="block w-full text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                            Nouveau Rapport
                        </a>
                        <a href="{{ route('doctor.patients.history', $patient->id) }}" class="block w-full text-center bg-white border border-slate-200 text-slate-600 py-2 rounded hover:bg-slate-50 transition">
                            Voir Historique ({{ $patient->reports->count() }})
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10 text-gray-500">
                    Vous n'avez pas encore de mrivs enregistrés.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
