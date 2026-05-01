@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Modération des Avis (Feedbacks)</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médecin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commentaire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($feedbacks as $feedback)
                        <tr class="{{ $feedback->is_reported ? 'bg-red-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $feedback->patient->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Dr. {{ $feedback->doctor->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-500 font-bold">
                                {{ str_repeat('⭐', $feedback->rating) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $feedback->comment }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($feedback->is_reported)
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">Signalé</span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">Normal</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form action="{{ route('admin.feedbacks.delete', $feedback->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cet avis définitivement ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Supprimer</button>
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
