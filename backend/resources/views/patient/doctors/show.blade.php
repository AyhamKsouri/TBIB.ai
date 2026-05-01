@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/dashboard" class="text-sm font-bold text-slate-400 hover:text-blue-600 transition-colors">Dashboard</a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-[10px] text-slate-300 mx-2"></i>
                    <a href="{{ route('public.doctors.index') }}" class="text-sm font-bold text-slate-400 hover:text-blue-600 transition-colors">Nos Tbibs</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-[10px] text-slate-300 mx-2"></i>
                    <span class="text-sm font-bold text-slate-900">Dr. {{ $doctor->user->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Doctor Info -->
        <div class="lg:col-span-2 space-y-8">
            <div class="modern-card p-1 bg-slate-50/50">
                <div class="bg-white rounded-[22px] p-8 md:p-12">
                    <div class="flex flex-col md:flex-row gap-10">
                        <div class="shrink-0 flex flex-col items-center">
                            <div class="relative">
                                <div class="absolute inset-0 gradient-primary blur-3xl opacity-20"></div>
                                <div class="relative w-40 h-40 gradient-primary rounded-[48px] flex items-center justify-center text-white font-black text-6xl shadow-2xl shadow-blue-500/20">
                                    {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
                                </div>
                                <div class="absolute -bottom-3 -right-3 w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-green-500 shadow-xl border border-slate-50">
                                    <i class="fas fa-check-circle text-xl"></i>
                                </div>
                            </div>
                            <div class="mt-8 text-center">
                                <div class="flex text-yellow-400 text-xs space-x-1 mb-2 justify-center">
                                    @for($i=0; $i<5; $i++) <i class="fas fa-star"></i> @endfor
                                </div>
                                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ $doctor->feedbacks->count() }} avis vérifiés</span>
                            </div>
                        </div>

                        <div class="flex-1">
                            <div class="inline-flex items-center px-4 py-1.5 rounded-xl bg-blue-50 text-blue-700 text-xs font-black uppercase tracking-widest mb-6">
                                {{ $doctor->department->name }}
                            </div>
                            <h1 class="text-4xl font-black text-slate-900 mb-4 tracking-tight">Dr. {{ $doctor->user->name }}</h1>
                            <p class="text-xl text-slate-500 font-bold mb-8">{{ $doctor->specialty }}</p>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="flex items-center p-4 rounded-2xl bg-slate-50 border border-slate-100/50">
                                    <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-blue-600 shadow-sm mr-4 shrink-0">
                                        <i class="fas fa-award text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Expérience</p>
                                        <p class="text-sm font-black text-slate-900">{{ $doctor->experience_years }} ans de pratique</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-4 rounded-2xl bg-slate-50 border border-slate-100/50">
                                    <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-blue-600 shadow-sm mr-4 shrink-0">
                                        <i class="fas fa-map-marker-alt text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Localisation</p>
                                        <p class="text-sm font-black text-slate-900">{{ $doctor->location ?? 'Centre Médical TBIB' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bio / About Section (Static for now as not in DB) -->
            <div class="modern-card p-10">
                <h3 class="text-2xl font-black text-slate-900 mb-6 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-4"></i>
                    À propos du praticien
                </h3>
                <p class="text-slate-500 leading-relaxed text-lg">
                    Le Dr. {{ $doctor->user->name }} est un expert reconnu en {{ $doctor->specialty }} au sein du département {{ $doctor->department->name }}. 
                    Avec plus de {{ $doctor->experience_years }} ans d'expérience, il s'engage à fournir des soins de haute qualité en utilisant les dernières avancées technologiques et une approche centrée sur le mriv.
                </p>
                <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Spécialités principales</h4>
                        <ul class="space-y-3">
                            <li class="flex items-center text-slate-700 font-bold">
                                <i class="fas fa-check text-blue-500 mr-3 text-xs"></i> {{ $doctor->specialty }}
                            </li>
                            <li class="flex items-center text-slate-700 font-bold">
                                <i class="fas fa-check text-blue-500 mr-3 text-xs"></i> Consultation préventive
                            </li>
                            <li class="flex items-center text-slate-700 font-bold">
                                <i class="fas fa-check text-blue-500 mr-3 text-xs"></i> Suivi thérapeutique
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Langues parlées</h4>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-slate-100 rounded-lg text-xs font-black text-slate-600 uppercase">Français</span>
                            <span class="px-3 py-1 bg-slate-100 rounded-lg text-xs font-black text-slate-600 uppercase">Arabe</span>
                            <span class="px-3 py-1 bg-slate-100 rounded-lg text-xs font-black text-slate-600 uppercase">Anglais</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feedbacks -->
            <div class="space-y-6">
                <h3 class="text-2xl font-black text-slate-900 flex items-center">
                    <i class="fas fa-comments text-blue-600 mr-4"></i>
                    Avis des Mrivs ({{ $doctor->feedbacks->count() }})
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($doctor->feedbacks as $feedback)
                        <div class="modern-card p-8 bg-white">
                            <div class="flex justify-between items-center mb-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center font-black text-slate-400 text-sm">
                                        {{ strtoupper(substr($feedback->patient->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-900">{{ $feedback->patient->user->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold">{{ \Carbon\Carbon::parse($feedback->created_at)->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex text-yellow-400 text-[10px]">
                                    @for($i = 0; $i < $feedback->rating; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-slate-500 text-sm leading-relaxed italic">"{{ $feedback->comment }}"</p>
                        </div>
                    @empty
                        <div class="md:col-span-2 modern-card p-12 text-center bg-slate-50/50 border-dashed">
                            <p class="text-slate-400 font-bold">Aucun avis pour le moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Booking Card -->
        <div class="space-y-8">
            <div class="sticky top-28">
                <div class="modern-card p-1 gradient-primary shadow-2xl shadow-blue-500/30">
                    <div class="bg-white rounded-[22px] p-8">
                        <h3 class="text-xl font-black text-slate-900 mb-6">Prendre rendez-vous</h3>
                        
                        <div class="space-y-6 mb-10">
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Jours de travail</p>
                                    <p class="text-sm font-black text-slate-900">{{ $doctor->work_days ?? 'Lun - Ven' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Horaires</p>
                                    <p class="text-sm font-black text-slate-900">{{ $doctor->work_hours ?? '08:00 - 17:00' }}</p>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('patient.appointments.create', ['doctor_id' => $doctor->id]) }}" 
                            class="btn-primary w-full !py-4 flex items-center justify-center space-x-3 mb-4">
                            <span>Réserver un créneau</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                        <p class="text-[10px] text-center text-slate-400 font-bold uppercase tracking-widest">Confirmation immédiate</p>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="modern-card p-8 mt-8 bg-slate-900 text-white">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-headset text-xl"></i>
                    </div>
                    <h4 class="text-lg font-black mb-2">Besoin d'aide ?</h4>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">Notre support est disponible 24/7 pour vous accompagner dans votre prise de rendez-vous.</p>
                    <a href="#" class="text-blue-400 font-black text-xs uppercase tracking-widest hover:text-blue-300 transition-colors">Contactez-nous</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
