@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Prendre un rendez-vous</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('patient.appointments.store') }}" method="POST" id="appointmentForm" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Département</label>
                    <select id="departmentSelect" class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les départements</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Médecin</label>
                    <select name="doctor_id" id="doctorSelect" required class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Choisir un médecin</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" data-dept="{{ $doctor->department_id }}">
                                Dr. {{ $doctor->user->name }} ({{ $doctor->specialty }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="date" required min="{{ date('Y-m-d') }}" class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Heure</label>
                    <select name="time" required class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        @for($i = 8; $i <= 17; $i++)
                            <option value="{{ sprintf('%02d:00', $i) }}">{{ sprintf('%02d:00', $i) }}</option>
                            <option value="{{ sprintf('%02d:30', $i) }}">{{ sprintf('%02d:30', $i) }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-md font-bold hover:bg-blue-700 transition duration-300">
                    Confirmer le rendez-vous
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('departmentSelect').addEventListener('change', function() {
        const deptId = this.value;
        const doctorSelect = document.getElementById('doctorSelect');
        const options = doctorSelect.querySelectorAll('option');

        options.forEach(option => {
            if (deptId === "" || option.dataset.dept === deptId || option.value === "") {
                option.style.display = "block";
            } else {
                option.style.display = "none";
            }
        });
        doctorSelect.value = "";
    });

    // Handle form submission via AJAX to show success/error without page reload if needed, 
    // or just let it submit normally. For simplicity here, normal submit.
    // Note: The form points to /api/appointments which expects a JSON response. 
    // We might want to add a web-specific store method or handle it with JS.
</script>
@endsection
