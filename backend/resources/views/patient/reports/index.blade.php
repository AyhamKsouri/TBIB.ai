@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Mon Dossier Médical</h2>

        <div class="space-y-6">
            @forelse($reports as $report)
                <div class="border rounded-lg p-6 hover:bg-gray-50 transition duration-150">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-blue-800">Rapport du {{ $report->created_at->format('d/m/Y') }}</h3>
                            <p class="text-sm text-gray-600">Dr. {{ $report->doctor->user->name }} ({{ $report->doctor->specialty }})</p>
                        </div>
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Réalisé</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2"><i class="fas fa-stethoscope mr-2"></i>Diagnostic</h4>
                            <p class="text-gray-600 bg-white p-3 border rounded shadow-sm">{{ $report->diagnosis }}</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2"><i class="fas fa-pills mr-2"></i>Prescription</h4>
                            <p class="text-gray-600 bg-white p-3 border rounded shadow-sm">{{ $report->prescription }}</p>
                        </div>
                    </div>

                    @if($report->notes)
                        <div class="mt-4">
                            <h4 class="font-semibold text-gray-700 mb-2"><i class="fas fa-sticky-note mr-2"></i>Notes additionnelles</h4>
                            <p class="text-gray-500 italic">{{ $report->notes }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-10 text-gray-500">
                    <i class="fas fa-folder-open text-4xl mb-4"></i>
                    <p>Aucun rapport médical disponible pour le moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
