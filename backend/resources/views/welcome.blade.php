@extends('layouts.app')

@section('content')
<div class="relative overflow-hidden">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-6 pt-20 pb-24 md:pt-32 md:pb-40">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div class="relative z-10">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 mb-8">
                    <span class="flex h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
                    <span class="text-xs font-black text-blue-700 uppercase tracking-widest">Nouveau : Consultation IA 2.0</span>
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-slate-900 leading-[1.1] mb-8">
                    Votre santé, <br/>
                    <span class="gradient-text">augmentée par l'IA</span>
                </h1>
                <p class="text-xl text-slate-500 leading-relaxed mb-10 max-w-xl">
                    TBIB révolutionne le parcours de soin. Une plateforme intelligente pour gérer vos rendez-vous, suivre votre santé et bénéficier d'une assistance médicale de pointe 24/7.
                </p>
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="/register" class="btn-primary text-center text-lg px-10">
                        Commencer l'expérience
                    </a>
                    <a href="/login" class="btn-secondary text-center text-lg px-10">
                        Se connecter
                    </a>
                </div>
                
                <div class="mt-12 flex items-center space-x-6">
                    <div class="flex -space-x-3">
                        <img class="w-10 h-10 rounded-full border-4 border-white object-cover" src="https://images.unsplash.com/photo-1559839734-2b71f1536783?auto=format&fit=crop&q=80&w=100" alt="Tbib">
                        <img class="w-10 h-10 rounded-full border-4 border-white object-cover" src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&q=80&w=100" alt="Tbib">
                        <img class="w-10 h-10 rounded-full border-4 border-white object-cover" src="https://images.unsplash.com/photo-1594824476967-48c8b964273f?auto=format&fit=crop&q=80&w=100" alt="Tbib">
                    </div>
                    <p class="text-sm text-slate-500 font-medium">
                        Rejoint par plus de <span class="text-slate-900 font-bold">2,000+</span> tbibs experts
                    </p>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -top-20 -right-20 w-96 h-96 bg-blue-200/50 rounded-full blur-3xl opacity-50"></div>
                <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-indigo-200/50 rounded-full blur-3xl opacity-50"></div>
                
                <div class="relative modern-card p-2 bg-slate-50/50 backdrop-blur-sm">
                    <div class="bg-white rounded-[22px] overflow-hidden">
                        <div class="p-8">
                            <div class="flex items-center justify-between mb-8">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 gradient-primary rounded-xl flex items-center justify-center text-white shadow-lg">
                                        <i class="fas fa-robot text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-slate-900">Assistant TBIB</h4>
                                        <div class="flex items-center text-[10px] text-green-500 font-black uppercase tracking-widest">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                                            En ligne
                                        </div>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <div class="w-2 h-2 rounded-full bg-slate-200"></div>
                                    <div class="w-2 h-2 rounded-full bg-slate-200"></div>
                                </div>
                            </div>
                            
                            <div class="space-y-6">
                                <div class="flex justify-start">
                                    <div class="bg-slate-100 p-4 rounded-2xl rounded-tl-none text-sm text-slate-600 max-w-[80%] leading-relaxed">
                                        "Bonjour, je ressens une douleur persistante à la poitrine depuis ce matin..."
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <div class="gradient-primary p-4 rounded-2xl rounded-tr-none text-sm text-white max-w-[80%] shadow-xl shadow-blue-500/20 leading-relaxed">
                                        "Je comprends. Basé sur vos antécédents, je vous suggère de consulter le Dr. Dupont (Cardiologue) immédiatement. Voulez-vous que je réserve un créneau ?"
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-8 pt-6 border-t border-slate-50">
                                <div class="flex space-x-2">
                                    <div class="h-10 bg-slate-50 rounded-xl flex-1 px-4 flex items-center text-xs text-slate-400">Tapez votre message...</div>
                                    <div class="w-10 h-10 gradient-primary rounded-xl flex items-center justify-center text-white shadow-md">
                                        <i class="fas fa-paper-plane text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="bg-slate-50/50 py-24 border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-blue-600 text-sm font-black uppercase tracking-[0.2em] mb-4 text-center">Pourquoi TBIB ?</h2>
                <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">Un écosystème médical complet pour une vie plus saine</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="modern-card p-10 group">
                    <div class="w-16 h-16 gradient-primary rounded-2xl flex items-center justify-center text-white text-2xl mb-8 shadow-xl shadow-blue-500/20 group-hover:scale-110 transition-transform">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4">Réservation Intelligente</h3>
                    <p class="text-slate-500 leading-relaxed">Filtrez par spécialité, consultez les avis et réservez en 3 clics. Votre agenda est toujours à jour.</p>
                </div>

                <div class="modern-card p-10 group">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-600 to-indigo-700 rounded-2xl flex items-center justify-center text-white text-2xl mb-8 shadow-xl shadow-purple-500/20 group-hover:scale-110 transition-transform">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4">IA Conversationnelle</h3>
                    <p class="text-slate-500 leading-relaxed">Notre assistant NLP analyse vos symptômes et vous oriente vers le bon spécialiste en temps réel.</p>
                </div>

                <div class="modern-card p-10 group">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center text-white text-2xl mb-8 shadow-xl shadow-emerald-500/20 group-hover:scale-110 transition-transform">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4">Sécurité Maximale</h3>
                    <p class="text-slate-500 leading-relaxed">Vos données de santé sont chiffrées de bout en bout et protégées par les plus hauts standards de confidentialité.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
