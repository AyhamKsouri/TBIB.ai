@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
        <div>
            <div class="flex items-center space-x-3 mb-4">
                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-black uppercase tracking-widest rounded-full">Prendre RDV</span>
            </div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-2">Nos Tbibs</h1>
            <p class="text-slate-500 text-lg">Trouvez le praticien idéal parmi nos experts certifiés.</p>
        </div>
        
        <div class="mt-6 md:mt-0">
            <div class="bg-blue-50 text-blue-700 px-6 py-4 rounded-2xl flex items-center space-x-4 border border-blue-100/50">
                <div class="w-10 h-10 gradient-primary rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                    <i class="fas fa-user-md text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-black uppercase tracking-widest opacity-70">Disponibilité</p>
                    <p class="text-sm font-black">{{ $doctors->count() }} Tbibs actifs</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres Modernes -->
    <div class="modern-card p-2 bg-slate-50/50 mb-12">
        <div class="bg-white rounded-[22px] p-6">
            <form action="{{ route('public.doctors.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                <div class="md:col-span-5 space-y-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Département</label>
                    <div class="relative">
                        <select name="department_id" class="w-full pl-5 pr-10 py-4 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-bold appearance-none">
                            <option value="">Tous les départements</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                    </div>
                </div>
                <div class="md:col-span-4 space-y-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Spécialité</label>
                    <div class="relative">
                        <input type="text" name="specialty" value="{{ request('specialty') }}" placeholder="ex: Cardiologie" 
                            class="w-full pl-5 pr-5 py-4 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-bold">
                    </div>
                </div>
                <div class="md:col-span-3">
                    <button type="submit" class="btn-primary w-full !py-4 flex items-center justify-center space-x-3">
                        <i class="fas fa-search text-xs"></i>
                        <span>Filtrer les experts</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des médecins -->
    <div class="space-y-8">
        @forelse($doctors as $doctor)
            <div class="modern-card group">
                <div class="md:flex">
                    <div class="md:shrink-0 bg-slate-50/80 p-10 flex flex-col items-center justify-center w-full md:w-64 border-r border-slate-100 group-hover:bg-blue-50/50 transition-colors duration-500">
                        <div class="relative">
                            <div class="absolute inset-0 gradient-primary blur-2xl opacity-20 group-hover:opacity-40 transition-opacity"></div>
                            <div class="relative w-32 h-32 gradient-primary rounded-[40px] flex items-center justify-center text-white font-black text-5xl shadow-2xl shadow-blue-500/20 transform group-hover:scale-105 transition-transform duration-500">
                                {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
                            </div>
                            <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-green-500 shadow-xl border border-slate-50">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="mt-6 flex flex-col items-center">
                            <div class="flex text-yellow-400 text-[10px] space-x-1 mb-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $doctor->feedbacks->count() }} avis</span>
                        </div>
                    </div>
                    
                    <div class="p-10 w-full">
                        <div class="flex flex-col lg:flex-row justify-between items-start gap-8">
                            <div class="flex-1">
                                <div class="inline-flex items-center px-3 py-1 rounded-lg bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest mb-4">
                                    {{ $doctor->department->name }}
                                </div>
                                <h2 class="text-3xl font-black text-slate-900 mb-3 group-hover:text-blue-600 transition-colors">Dr. {{ $doctor->user->name }}</h2>
                                <div class="flex flex-wrap items-center gap-6 text-sm font-bold text-slate-500">
                                    <span class="flex items-center"><i class="fas fa-stethoscope mr-2.5 text-blue-500/50"></i>{{ $doctor->specialty }}</span>
                                    <span class="flex items-center"><i class="fas fa-award mr-2.5 text-blue-500/50"></i>{{ $doctor->experience_years }} ans d'expérience</span>
                                </div>
                                
                                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-2xl">
                                    <div class="flex items-center p-3 rounded-xl bg-slate-50 text-slate-600 text-xs font-bold border border-transparent group-hover:border-slate-100 transition-all">
                                        <i class="fas fa-map-marker-alt w-8 h-8 rounded-lg bg-white flex items-center justify-center text-blue-500 shadow-sm mr-3"></i>
                                        {{ $doctor->location ?? 'Centre Médical TBIB' }}
                                    </div>
                                    <div class="flex items-center p-3 rounded-xl bg-slate-50 text-slate-600 text-xs font-bold border border-transparent group-hover:border-slate-100 transition-all">
                                        <i class="fas fa-calendar-alt w-8 h-8 rounded-lg bg-white flex items-center justify-center text-blue-500 shadow-sm mr-3"></i>
                                        {{ $doctor->work_days ?? 'Lun - Ven' }} ({{ $doctor->work_hours ?? '08:00 - 17:00' }})
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row lg:flex-col gap-3 w-full lg:w-auto">
                                <a href="{{ route('patient.appointments.create', ['doctor_id' => $doctor->id]) }}" 
                                    class="btn-primary !px-10 !py-4 text-center whitespace-nowrap shadow-blue-500/20">
                                    Prendre RDV
                                </a>
                                <a href="{{ route('public.doctors.show', $doctor->id) }}" class="btn-secondary !px-10 !py-4 text-center whitespace-nowrap">
                                    Voir Profil
                                </a>
                            </div>
                        </div>

                        <!-- Mini Feedbacks Section -->
                        @if($doctor->feedbacks->isNotEmpty())
                            <div class="mt-10 pt-8 border-t border-slate-100">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest flex items-center">
                                        <i class="fas fa-comments text-blue-500 mr-2.5"></i>
                                        Derniers retours
                                    </h3>
                                    <a href="{{ route('public.doctors.show', $doctor->id) }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">Voir tout</a>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($doctor->feedbacks->take(2) as $feedback)
                                        <div class="p-5 rounded-2xl bg-slate-50/50 border border-slate-100/50 group-hover:bg-white transition-colors duration-500">
                                            <div class="flex justify-between items-center mb-3">
                                                <span class="font-black text-slate-900 text-[10px]">{{ $feedback->patient->user->name }}</span>
                                                <div class="flex text-yellow-400 text-[8px]">
                                                    @for($i = 0; $i < $feedback->rating; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <p class="text-slate-500 text-xs leading-relaxed italic">"{{ $feedback->comment }}"</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="modern-card p-20 text-center">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8">
                    <i class="fas fa-user-md text-4xl text-slate-200"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-2">Aucun Tbib trouvé</h3>
                <p class="text-slate-500 max-w-sm mx-auto">Nous n'avons trouvé aucun spécialiste correspondant à vos critères de recherche.</p>
                <a href="{{ route('public.doctors.index') }}" class="btn-primary mt-8 inline-block">Réinitialiser les filtres</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
