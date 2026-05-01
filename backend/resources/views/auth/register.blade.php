@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-16 md:py-24">
    <div class="text-center mb-16">
        <div class="inline-flex items-center justify-center w-20 h-20 gradient-primary rounded-[28px] shadow-2xl shadow-blue-500/30 mb-8 transform hover:rotate-6 transition-transform">
            <i class="fas fa-hand-holding-medical text-white text-3xl"></i>
        </div>
        <h1 class="text-5xl font-black text-slate-900 tracking-tight mb-4">Rejoindre TBIB</h1>
        <p class="text-xl text-slate-500 font-medium max-w-lg mx-auto leading-relaxed">Commencez votre parcours vers une santé mieux gérée par l'intelligence artificielle.</p>
    </div>

    <div class="modern-card p-1 md:p-2 bg-slate-50/50">
        <div class="bg-white rounded-[22px] p-8 md:p-12">
            @if($errors->any())
                <div class="bg-red-50 border border-red-100 text-red-600 px-6 py-4 rounded-2xl mb-10 flex items-start space-x-3">
                    <i class="fas fa-exclamation-circle mt-1"></i>
                    <ul class="text-sm font-bold">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/register" method="POST" id="registerForm" class="space-y-12">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20">
                    <!-- Informations de base -->
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-xl font-black text-slate-900 mb-6 flex items-center">
                                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm mr-3">1</span>
                                Informations personnelles
                            </h3>
                            <div class="space-y-5">
                                <div class="space-y-2">
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Nom complet</label>
                                    <input type="text" name="name" value="{{ old('name') }}" required 
                                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required 
                                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Mot de passe</label>
                                        <input type="password" name="password" required 
                                            class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Confirmation</label>
                                        <input type="password" name="password_confirmation" required 
                                            class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Choix du rôle et champs spécifiques -->
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-xl font-black text-slate-900 mb-6 flex items-center">
                                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm mr-3">2</span>
                                Votre Profil
                            </h3>
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Je souhaite m'inscrire en tant que :</label>
                                    <div class="grid grid-cols-2 gap-3 p-1.5 bg-slate-50 rounded-[20px]">
                                        <button type="button" onclick="setRole('patient')" id="btn-role-patient" 
                                            class="role-btn py-3 px-4 rounded-[14px] text-sm font-black uppercase tracking-widest transition-all bg-white shadow-sm text-blue-600">
                                            Mriv
                                        </button>
                                        <button type="button" onclick="setRole('doctor')" id="btn-role-doctor" 
                                            class="role-btn py-3 px-4 rounded-[14px] text-sm font-black uppercase tracking-widest transition-all text-slate-400 hover:text-slate-600">
                                            Tbib
                                        </button>
                                        <input type="hidden" name="role" id="roleInput" value="patient">
                                    </div>
                                </div>

                                <!-- Champs Mriv -->
                                <div id="patientFields" class="space-y-5 animate-in fade-in slide-in-from-top-4 duration-500">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Âge</label>
                                            <input type="number" name="age" value="{{ old('age') }}" 
                                                class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Genre</label>
                                            <select name="gender" class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium appearance-none">
                                                <option value="M">Homme</option>
                                                <option value="F">Femme</option>
                                                <option value="Autre">Autre</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="p-6 bg-blue-50/50 rounded-3xl border border-blue-100/50">
                                        <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-4">Dossier médical rapide</h4>
                                        <div class="space-y-4">
                                            <textarea name="persistent_sickness" rows="2" class="w-full px-4 py-3 bg-white/80 border-none rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium" placeholder="Maladies persistantes / Antécédents">{{ old('persistent_sickness') }}</textarea>
                                            <textarea name="allergies" rows="2" class="w-full px-4 py-3 bg-white/80 border-none rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium" placeholder="Allergies connues">{{ old('allergies') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Champs Tbib -->
                                <div id="doctorFields" class="hidden space-y-5 animate-in fade-in slide-in-from-top-4 duration-500">
                                    <div class="space-y-2">
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Spécialité</label>
                                        <input type="text" name="specialty" value="{{ old('specialty') }}" placeholder="ex: Cardiologue"
                                            class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Expérience (ans)</label>
                                            <input type="number" name="experience_years" value="{{ old('experience_years') }}" 
                                                class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Département</label>
                                            <select name="department_id" class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium appearance-none">
                                                @foreach(\App\Models\Department::all() as $dept)
                                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-8 flex flex-col items-center space-y-6">
                    <button type="submit" class="w-full md:w-auto md:min-w-[300px] btn-primary !py-5 text-lg shadow-blue-500/30">
                        Créer mon compte
                    </button>
                    <p class="text-slate-500 text-sm font-medium">
                        Déjà inscrit ? <a href="/login" class="text-blue-600 font-black hover:text-blue-700 transition-colors ml-1">Se connecter</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function setRole(role) {
        const input = document.getElementById('roleInput');
        const patientFields = document.getElementById('patientFields');
        const doctorFields = document.getElementById('doctorFields');
        const btnPatient = document.getElementById('btn-role-patient');
        const btnDoctor = document.getElementById('btn-role-doctor');
        
        input.value = role;
        
        if (role === 'patient') {
            patientFields.classList.remove('hidden');
            doctorFields.classList.add('hidden');
            btnPatient.classList.add('bg-white', 'shadow-sm', 'text-blue-600');
            btnPatient.classList.remove('text-slate-400');
            btnDoctor.classList.remove('bg-white', 'shadow-sm', 'text-blue-600');
            btnDoctor.classList.add('text-slate-400');
        } else {
            patientFields.classList.add('hidden');
            doctorFields.classList.remove('hidden');
            btnDoctor.classList.add('bg-white', 'shadow-sm', 'text-blue-600');
            btnDoctor.classList.remove('text-slate-400');
            btnPatient.classList.remove('bg-white', 'shadow-sm', 'text-blue-600');
            btnPatient.classList.add('text-slate-400');
        }
    }
    
    // Check initial state
    @if(old('role') == 'doctor')
        setRole('doctor');
    @endif
</script>
@endsection
