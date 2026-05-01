@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <h2 class="text-3xl font-bold text-gray-800 mb-8">Tableau de Bord Administrateur</h2>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-600">
            <div class="text-gray-500 text-sm font-medium uppercase">Mrivs</div>
            <div class="text-3xl font-bold text-gray-800">{{ $stats['total_patients'] }}</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-600">
            <div class="text-gray-500 text-sm font-medium uppercase">Tbibs</div>
            <div class="text-3xl font-bold text-gray-800">{{ $stats['total_doctors'] }}</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-purple-600">
            <div class="text-gray-500 text-sm font-medium uppercase">Rendez-vous</div>
            <div class="text-3xl font-bold text-gray-800">{{ $stats['total_appointments'] }}</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-red-600">
            <div class="text-gray-500 text-sm font-medium uppercase">Actions (Logs)</div>
            <div class="text-3xl font-bold text-gray-800">{{ count($recent_logs) }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <!-- Logs récents -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Activités Récentes</h3>
            <div class="space-y-4">
                @foreach($recent_logs as $log)
                    <div class="flex items-start space-x-3 text-sm border-b pb-2">
                        <div class="bg-gray-100 rounded p-2"><i class="fas fa-history text-gray-500"></i></div>
                        <div>
                            <p class="text-gray-800 font-medium">{{ $log->user->name }} a effectué l'action : <span class="text-blue-600">{{ $log->action }}</span></p>
                            <p class="text-gray-500 text-xs">{{ $log->timestamp }} - IP: {{ $log->ip_address }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('admin.audit.index') }}" class="block text-center mt-4 text-blue-600 hover:underline">Voir tous les logs</a>
        </div>

        <!-- Gestion rapide -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Gestion des Ressources</h3>
            <div class="grid grid-cols-1 gap-4">
                <a href="{{ route('admin.doctors.index') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <span class="font-medium">Gérer les Tbibs</span>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </a>
                <a href="{{ route('admin.patients.index') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <span class="font-medium">Gérer les Mrivs</span>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </a>
                <a href="{{ route('admin.feedbacks.index') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <span class="font-medium">Modérer les Avis</span>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </a>
                <a href="#" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition opacity-50 cursor-not-allowed">
                    <span class="font-medium">Départements (Bientôt)</span>
                    <i class="fas fa-lock text-gray-400"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
