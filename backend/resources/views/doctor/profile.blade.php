@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Mon Profil Professionnel</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('doctor.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Spécialité</label>
                    <input type="text" name="specialty" value="{{ old('specialty', $doctor->specialty) }}" required class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Années d'expérience</label>
                    <input type="number" name="experience_years" value="{{ old('experience_years', $doctor->experience_years) }}" required class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone', $doctor->phone) }}" class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Localisation (Cabinet)</label>
                    <input type="text" name="location" value="{{ old('location', $doctor->location) }}" class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Jours de travail</label>
                    <input type="text" name="work_days" value="{{ old('work_days', $doctor->work_days) }}" placeholder="ex: Lun - Ven" class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Horaires de travail</label>
                    <input type="text" name="work_hours" value="{{ old('work_hours', $doctor->work_hours) }}" placeholder="ex: 08:00 - 17:00" class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Département</label>
                    <select name="department_id" required class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ $doctor->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-md font-bold hover:bg-blue-700 transition duration-300">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
