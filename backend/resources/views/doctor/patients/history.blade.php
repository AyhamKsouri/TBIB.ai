@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
        <div>
            <div class="flex items-center space-x-3 mb-4">
                <a href="{{ route('doctor.patients.index') }}" class="text-blue-600 hover:underline flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Retour aux Mrivs
                </a>
            </div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-2">Historique Patient</h1>
            <p class="text-slate-500 text-lg">Suivi de santé pour <span class="font-bold text-slate-900">{{ $patient->user->name }}</span></p>
        </div>
        
        <div class="mt-6 md:mt-0 flex items-center space-x-4">
            <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100/50">
                <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Total Sessions</p>
                <p class="text-2xl font-black text-slate-900">{{ $reports->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Patient Quick Info -->
    <div class="modern-card p-8 mb-12 bg-slate-50/50 border-slate-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Pathologies persistantes</span>
                <p class="text-sm font-bold text-slate-700 bg-white p-3 rounded-xl border border-slate-100">{{ $patient->persistent_sickness ?? 'Aucune' }}</p>
            </div>
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Allergies connues</span>
                <p class="text-sm font-bold text-slate-700 bg-white p-3 rounded-xl border border-slate-100">{{ $patient->allergies ?? 'Aucune' }}</p>
            </div>
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Traitements actuels</span>
                <p class="text-sm font-bold text-slate-700 bg-white p-3 rounded-xl border border-slate-100">{{ $patient->current_treatments ?? 'Aucun' }}</p>
            </div>
        </div>
    </div>

    <!-- Timeline of Sessions -->
    <div class="space-y-8 relative before:absolute before:left-8 before:top-0 before:bottom-0 before:w-0.5 before:bg-slate-100">
        @forelse($reports as $index => $report)
            <div class="relative pl-20">
                <!-- Session Indicator -->
                <div class="absolute left-0 top-0 w-16 h-16 bg-white border-4 border-blue-50 rounded-2xl flex flex-col items-center justify-center shadow-sm z-10">
                    <span class="text-[10px] font-black text-blue-600 uppercase leading-none">Session</span>
                    <span class="text-xl font-black text-slate-900 mt-1">{{ $reports->count() - $index }}</span>
                </div>

                <div class="modern-card p-0 overflow-hidden">
                    <!-- Session Header -->
                    <div class="bg-slate-50 px-8 py-4 border-b border-slate-100 flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-calendar-alt text-slate-400"></i>
                            <span class="text-sm font-black text-slate-900">{{ \Carbon\Carbon::parse($report->created_at)->translatedFormat('d F Y') }}</span>
                            <span class="text-xs font-bold text-slate-400">— {{ \Carbon\Carbon::parse($report->created_at)->format('H:i') }}</span>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                            <!-- Medical Content -->
                            <div class="space-y-6">
                                <div>
                                    <h4 class="text-xs font-black text-blue-600 uppercase tracking-widest mb-3 flex items-center">
                                        <i class="fas fa-stethoscope mr-2"></i> Diagnostic
                                    </h4>
                                    <p class="text-slate-700 leading-relaxed font-medium bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                                        {{ $report->diagnosis }}
                                    </p>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black text-emerald-600 uppercase tracking-widest mb-3 flex items-center">
                                        <i class="fas fa-pills mr-2"></i> Prescription
                                    </h4>
                                    <p class="text-slate-700 leading-relaxed font-medium bg-emerald-50/30 p-4 rounded-2xl border border-emerald-100/50">
                                        {{ $report->prescription }}
                                    </p>
                                </div>
                            </div>

                            <!-- AI Summary Section -->
                            <div class="bg-blue-50/30 rounded-[2rem] p-8 border border-blue-100/50 relative overflow-hidden">
                                <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/5 rounded-full blur-2xl"></div>
                                <h4 class="text-xs font-black text-blue-700 uppercase tracking-widest mb-6 flex items-center">
                                    <i class="fas fa-robot mr-3 text-lg"></i> Résumé Intelligent (IA)
                                </h4>
                                <div class="prose prose-blue prose-sm text-slate-600 leading-relaxed">
                                    @if($report->ai_summary)
                                        {!! nl2br(e($report->ai_summary)) !!}
                                    @else
                                        <p class="italic text-slate-400">Aucun résumé IA disponible pour cette session.</p>
                                    @endif
                                </div>
                                <div class="mt-8 pt-6 border-t border-blue-100/50">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Généré à partir des notes cliniques</p>
                                </div>
                            </div>
                        </div>

                        @if($report->notes)
                            <div class="mt-8 pt-8 border-t border-slate-50">
                                <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Notes confidentielles</h4>
                                <p class="text-slate-500 text-sm leading-relaxed">{{ $report->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="modern-card p-20 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-notes-medical text-3xl text-slate-200"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2">Aucun historique disponible</h3>
                <p class="text-slate-500">Aucune session médicale n'a encore été enregistrée pour ce mriv.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
