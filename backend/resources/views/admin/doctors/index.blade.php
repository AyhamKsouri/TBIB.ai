@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Gestion des Tbibs</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tbib</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Spécialité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($doctors as $doctor)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $doctor->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $doctor->specialty }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if(!$doctor->is_validated)
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold">En attente</span>
                                @elseif(!$doctor->user->is_active)
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">Désactivé</span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">Actif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                @if(!$doctor->is_validated)
                                    <form action="{{ route('admin.doctors.validate', $doctor->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900 font-bold">Valider</button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.users.toggle', $doctor->user_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="{{ $doctor->user->is_active ? 'text-red-600' : 'text-blue-600' }} hover:underline font-bold">
                                        {{ $doctor->user->is_active ? 'Désactiver' : 'Activer' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
