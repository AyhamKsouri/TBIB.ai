@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
        <div>
            <div class="flex items-center space-x-3 mb-4">
                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-black uppercase tracking-widest rounded-full">Vue d'ensemble</span>
            </div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Tableau de Bord</h1>
            <p class="text-slate-500 mt-2 text-lg">Ravi de vous revoir, <span class="font-bold text-slate-900">{{ Auth::user()->name }}</span>.</p>
        </div>
        
        <div class="mt-6 md:mt-0 flex items-center space-x-4">
            <div class="text-right hidden sm:block">
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1">Dernière connexion</p>
                <p class="text-sm font-black text-slate-900">{{ now()->format('d M, H:i') }}</p>
            </div>
            <div class="w-12 h-12 gradient-primary rounded-2xl flex items-center justify-center text-white shadow-lg">
                <i class="fas fa-user-circle text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Rôles spécifiques -->
        @if(Auth::user()->isPatient())
            <div class="modern-card p-10 group">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-3xl mb-8 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-4">Prendre Rendez-vous</h3>
                <p class="text-slate-500 mb-8 leading-relaxed">Trouvez un spécialiste et réservez votre créneau en quelques secondes.</p>
                <a href="{{ route('public.doctors.index') }}" class="btn-secondary !py-2.5 w-full justify-center inline-flex items-center">
                    Réserver maintenant <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>

            <div class="modern-card p-10 group">
                <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-3xl mb-8 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-history"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-4">Mes Rendez-vous</h3>
                <p class="text-slate-500 mb-8 leading-relaxed">Consultez vos rendez-vous à venir et gérez vos reprogrammations.</p>
                <a href="{{ route('patient.appointments.index') }}" class="btn-secondary !py-2.5 w-full justify-center inline-flex items-center hover:!bg-emerald-50 hover:!text-emerald-700 hover:!border-emerald-200">
                    Voir l'historique <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>

            <div class="modern-card p-10 group">
                <div class="w-16 h-16 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-3xl mb-8 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-file-medical-alt"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-4">Dossier Médical</h3>
                <p class="text-slate-500 mb-8 leading-relaxed">Accédez à vos rapports, diagnostics et prescriptions numériques.</p>
                <a href="{{ route('patient.reports.index') }}" class="btn-secondary !py-2.5 w-full justify-center inline-flex items-center hover:!bg-purple-50 hover:!text-purple-700 hover:!border-purple-200">
                    Mes rapports <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>
        @endif

        @if(Auth::user()->isDoctor())
            <div class="modern-card p-10 group">
                <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-3xl mb-8 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-4">Mon Agenda</h3>
                <p class="text-slate-500 mb-8 leading-relaxed">Gérez vos consultations du jour et répondez aux demandes.</p>
                <a href="{{ route('doctor.appointments.index') }}" class="btn-secondary !py-2.5 w-full justify-center inline-flex items-center">
                    Voir l'agenda <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>

            <div class="modern-card p-10 group">
                <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-3xl mb-8 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-user-injured"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-4">Mes Mrivs</h3>
                <p class="text-slate-500 mb-8 leading-relaxed">Accédez aux dossiers mrivs et créez de nouveaux rapports.</p>
                <a href="{{ route('doctor.patients.index') }}" class="btn-secondary !py-2.5 w-full justify-center inline-flex items-center">
                    Liste mrivs <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>

            <div class="modern-card p-10 group">
                <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-3xl mb-8 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-user-md"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-4">Profil Professionnel</h3>
                <p class="text-slate-500 mb-8 leading-relaxed">Mettez à jour vos horaires, votre localisation et spécialité.</p>
                <a href="{{ route('doctor.profile') }}" class="btn-secondary !py-2.5 w-full justify-center inline-flex items-center">
                    Gérer mon profil <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>
        @endif

        @if(Auth::user()->isAdmin())
            <div class="modern-card p-12 group lg:col-span-3 flex flex-col md:flex-row items-center justify-between overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-red-50 rounded-full -mr-32 -mt-32 opacity-50"></div>
                <div class="flex items-center space-x-8 relative z-10">
                    <div class="w-20 h-20 bg-red-100 text-red-600 rounded-3xl flex items-center justify-center text-4xl shadow-inner">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-black text-slate-900 mb-2">Espace Administration</h3>
                        <p class="text-slate-500 text-lg">Supervisez les utilisateurs, validez les praticiens et modérez le contenu.</p>
                    </div>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="mt-8 md:mt-0 btn-primary !px-10 !py-4 text-lg relative z-10 !from-red-600 !to-rose-700 shadow-red-500/20">
                    Ouvrir le Panel Admin
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
