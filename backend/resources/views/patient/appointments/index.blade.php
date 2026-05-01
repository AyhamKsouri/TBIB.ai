@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
        <div>
            <div class="flex items-center space-x-3 mb-4">
                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-black uppercase tracking-widest rounded-full">Suivi Santé</span>
            </div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-2">Mes Rendez-vous</h1>
            <p class="text-slate-500 text-lg">Gérez vos consultations passées et à venir.</p>
        </div>
        
        <div class="mt-6 md:mt-0">
            <a href="{{ route('public.doctors.index') }}" class="btn-primary flex items-center space-x-3">
                <i class="fas fa-plus text-xs"></i>
                <span>Nouveau Rendez-vous</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-2xl mb-8 flex items-center space-x-3 animate-in fade-in slide-in-from-top-4 duration-500">
            <i class="fas fa-check-circle"></i>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-100 text-red-600 px-6 py-4 rounded-2xl mb-8 flex items-center space-x-3 animate-in fade-in slide-in-from-top-4 duration-500">
            <i class="fas fa-exclamation-circle"></i>
            <span class="font-bold text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <div class="modern-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Date & Heure</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tbib</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Statut</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Via</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($appointments as $appointment)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex flex-col items-center justify-center text-blue-600 border border-blue-100/50">
                                        <span class="text-[10px] font-black uppercase leading-none">{{ \Carbon\Carbon::parse($appointment->date)->format('M') }}</span>
                                        <span class="text-lg font-black leading-none mt-1">{{ \Carbon\Carbon::parse($appointment->date)->format('d') }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-black text-slate-900">{{ \Carbon\Carbon::parse($appointment->date)->translatedFormat('l d F Y') }}</div>
                                        <div class="text-xs font-bold text-slate-400 mt-1"><i class="far fa-clock mr-1.5"></i>{{ $appointment->time }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 gradient-primary rounded-xl flex items-center justify-center text-white text-xs font-black shadow-lg shadow-blue-500/10">
                                        {{ strtoupper(substr($appointment->doctor->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-black text-slate-900">Dr. {{ $appointment->doctor->user->name }}</div>
                                        <div class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mt-0.5">{{ $appointment->doctor->specialty }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest inline-flex items-center space-x-2
                                    {{ $appointment->status === 'scheduled' ? 'bg-blue-50 text-blue-600 border border-blue-100' : '' }}
                                    {{ $appointment->status === 'cancelled' ? 'bg-red-50 text-red-600 border border-red-100' : '' }}
                                    {{ $appointment->status === 'completed' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : '' }}
                                    {{ $appointment->status === 'pending_repro' ? 'bg-amber-50 text-amber-600 border border-amber-100' : '' }}">
                                    @if($appointment->status === 'pending_repro')
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse mr-2"></span>
                                        Proposition
                                    @else
                                        <span class="w-1.5 h-1.5 rounded-full mr-2 
                                            {{ $appointment->status === 'scheduled' ? 'bg-blue-500' : '' }}
                                            {{ $appointment->status === 'cancelled' ? 'bg-red-500' : '' }}
                                            {{ $appointment->status === 'completed' ? 'bg-emerald-500' : '' }}"></span>
                                        {{ $appointment->status }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-2">
                                    @if($appointment->booked_via === 'ai_assistant')
                                        <div class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-[10px]">
                                            <i class="fas fa-robot"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-500">IA</span>
                                    @else
                                        <div class="w-6 h-6 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-[10px]">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-500">Manuel</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                @if($appointment->status === 'scheduled')
                                    <form action="{{ route('patient.appointments.destroy', $appointment->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition-colors" onclick="return confirm('Annuler ce rendez-vous ?')">
                                            <i class="fas fa-calendar-times text-lg"></i>
                                        </button>
                                    </form>
                                @elseif($appointment->status === 'pending_repro')
                                    <div class="flex flex-col items-end space-y-3">
                                        <div class="text-[10px] font-black text-amber-600 uppercase tracking-widest bg-amber-50 px-2 py-1 rounded-md">Nouvel horaire suggéré</div>
                                        <div class="text-xs font-bold text-slate-600 mb-2">{{ \Carbon\Carbon::parse($appointment->suggested_date)->format('d M') }} à {{ $appointment->suggested_time }}</div>
                                        <div class="flex space-x-2">
                                            <form action="{{ route('patient.appointments.handle_repro', $appointment->id) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="action" value="accept">
                                                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-black shadow-lg shadow-emerald-500/20 hover:scale-105 active:scale-95 transition-all">Accepter</button>
                                            </form>
                                            <form action="{{ route('patient.appointments.handle_repro', $appointment->id) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="action" value="reject">
                                                <button type="submit" class="px-4 py-2 bg-white text-red-600 border border-red-100 rounded-xl text-xs font-black hover:bg-red-50 transition-all">Refuser</button>
                                            </form>
                                        </div>
                                    </div>
                                @elseif($appointment->status === 'completed')
                                    <button onclick="openFeedbackModal({{ $appointment->doctor_id }}, 'Dr. {{ $appointment->doctor->user->name }}')" 
                                        class="px-5 py-2.5 bg-blue-50 text-blue-600 rounded-xl text-xs font-black hover:bg-blue-100 transition-all flex items-center space-x-2 ml-auto">
                                        <i class="fas fa-star text-[10px]"></i>
                                        <span>Donner mon avis</span>
                                    </button>
                                @else
                                    <span class="text-xs font-bold text-slate-300">Terminé</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="far fa-calendar-times text-2xl text-slate-200"></i>
                                </div>
                                <h3 class="text-lg font-black text-slate-900 mb-2">Aucun rendez-vous</h3>
                                <p class="text-slate-500 text-sm max-w-xs mx-auto mb-8">Vous n'avez pas encore de rendez-vous programmé ou passé.</p>
                                <a href="{{ route('public.doctors.index') }}" class="btn-primary inline-flex">Trouver un Tbib</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Feedback Modal -->
<div id="feedbackModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm overflow-y-auto h-full w-full z-50 animate-in fade-in duration-300">
    <div class="relative top-20 mx-auto p-2 w-full max-w-[440px]">
        <div class="bg-white rounded-[32px] shadow-2xl overflow-hidden">
            <div class="gradient-primary p-8 text-white relative">
                <button onclick="closeFeedbackModal()" class="absolute top-6 right-6 text-white/60 hover:text-white transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-md">
                    <i class="fas fa-star text-2xl"></i>
                </div>
                <h3 class="text-2xl font-black tracking-tight" id="modalTitle">Donner votre avis</h3>
                <p class="text-blue-100 text-sm mt-2 font-medium">Partagez votre expérience pour aider les autres mrivs.</p>
            </div>

            <div class="p-8">
                <form action="{{ route('patient.feedback.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="doctor_id" id="modalDoctorId">
                    
                    <div class="space-y-3">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Quelle note donneriez-vous ?</label>
                        <div class="grid grid-cols-5 gap-2">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="rating" value="{{ $i }}" class="peer sr-only" {{ $i == 5 ? 'checked' : '' }}>
                                    <div class="w-full py-3 flex flex-col items-center rounded-xl bg-slate-50 border-2 border-transparent peer-checked:bg-blue-50 peer-checked:border-blue-600 transition-all group-hover:bg-slate-100">
                                        <i class="fas fa-star text-sm text-slate-300 peer-checked:text-blue-600"></i>
                                        <span class="text-[10px] font-black mt-1 text-slate-400 peer-checked:text-blue-600">{{ $i }}</span>
                                    </div>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Votre commentaire</label>
                        <textarea name="comment" required rows="4" 
                            class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium" 
                            placeholder="Partagez votre expérience avec ce Tbib..."></textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full btn-primary !py-4 shadow-blue-500/30">
                            Envoyer mon avis
                        </button>
                        <button type="button" onclick="closeFeedbackModal()" class="w-full mt-3 text-sm font-black text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest py-2">
                            Peut-être plus tard
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openFeedbackModal(doctorId, doctorName) {
        document.getElementById('modalDoctorId').value = doctorId;
        document.getElementById('modalTitle').textContent = 'Avis sur ' + doctorName;
        document.getElementById('feedbackModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeFeedbackModal() {
        document.getElementById('feedbackModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('feedbackModal');
        if (event.target == modal) {
            closeFeedbackModal();
        }
    }
</script>
@endsection