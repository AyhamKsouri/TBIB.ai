@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[calc(100vh-80px)] px-6 py-12">
    <div class="w-full max-w-[440px]">
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 gradient-primary rounded-[28px] shadow-2xl shadow-blue-500/30 mb-8 transform hover:rotate-6 transition-transform">
                <i class="fas fa-hand-holding-medical text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-3">Bon retour</h1>
            <p class="text-slate-500 font-medium">Connectez-vous pour accéder à votre espace santé</p>
        </div>

        <div class="modern-card p-10 bg-white shadow-2xl shadow-slate-200/60">
            @if($errors->any())
                <div class="bg-red-50 border border-red-100 text-red-600 px-5 py-4 rounded-2xl mb-8 flex items-start space-x-3">
                    <i class="fas fa-exclamation-circle mt-1"></i>
                    <ul class="text-sm font-bold">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/login" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Adresse Email</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fas fa-envelope text-sm"></i>
                        </span>
                        <input type="email" name="email" required placeholder="nom@exemple.com" 
                            class="w-full pl-12 pr-5 py-4 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium">
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center px-1">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Mot de passe</label>
                        <a href="#" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:text-blue-700 transition-colors">Oublié ?</a>
                    </div>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fas fa-lock text-sm"></i>
                        </span>
                        <input type="password" name="password" required placeholder="••••••••" 
                            class="w-full pl-12 pr-5 py-4 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full btn-primary !py-4 text-base shadow-blue-500/30">
                        Se connecter
                    </button>
                </div>
            </form>

            <div class="mt-10 pt-8 border-t border-slate-50">
                <p class="text-center text-slate-500 text-sm font-medium">
                    Pas encore de compte ? 
                    <a href="/register" class="text-blue-600 font-black hover:text-blue-700 transition-colors ml-1">Rejoindre TBIB</a>
                </p>
            </div>
        </div>
        
        <p class="mt-10 text-center text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em]">
            &copy; 2026 TBIB Medical AI. Tous droits réservés.
        </p>
    </div>
</div>
@endsection
