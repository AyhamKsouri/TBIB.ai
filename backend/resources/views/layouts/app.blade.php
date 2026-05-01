<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TBIB - Excellence Médicale & IA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen flex flex-col selection:bg-blue-100 selection:text-blue-900">
    <!-- Navbar -->
    <nav class="glass-effect sticky top-0 z-50 py-4">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-3 group">
                        <div class="gradient-primary p-2.5 rounded-2xl shadow-lg shadow-blue-500/20 group-hover:rotate-6 transition-transform duration-300">
                            <i class="fas fa-hand-holding-medical text-white text-xl"></i>
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-slate-900">TBIB<span class="text-blue-600">.AI</span></span>
                    </a>
                </div>
                
                <div class="hidden md:flex items-center space-x-1">
                    @auth
                        @if(Auth::user()->isPatient())
                            <a href="{{ route('public.doctors.index') }}" class="px-4 py-2 text-sm font-bold text-slate-600 hover:text-blue-600 rounded-xl hover:bg-blue-50 transition-all">Trouver un Tbib</a>
                        @endif
                        <a href="/dashboard" class="px-4 py-2 text-sm font-bold text-slate-600 hover:text-blue-600 rounded-xl hover:bg-blue-50 transition-all">Dashboard</a>
                        
                        <div class="w-px h-6 bg-slate-200 mx-4"></div>
                        
                        <div class="flex items-center space-x-4 pl-2">
                            <div class="text-right">
                                <p class="text-xs font-black text-slate-900 leading-none">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-slate-500 uppercase tracking-widest mt-1 font-bold">
                                    {{ Auth::user()->role === 'patient' ? 'Mriv' : (Auth::user()->role === 'doctor' ? 'Tbib' : Auth::user()->role) }}
                                </p>
                            </div>
                            <form action="/logout" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="p-2.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-red-50 hover:text-red-600 transition-all">
                                    <i class="fas fa-power-off"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="/login" class="px-6 py-2.5 text-sm font-bold text-slate-600 hover:text-blue-600 transition-colors">Connexion</a>
                        <a href="/register" class="btn-primary !py-2.5 !px-8 text-sm">
                            S'inscrire
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-200 py-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-2">
                    <span class="text-xl font-bold text-slate-900">TBIB<span class="gradient-text">.AI</span></span>
                    <span class="text-slate-400 text-sm">| Excellence en santé assistée par IA</span>
                </div>
                <div class="text-slate-500 text-sm">
                    &copy; 2026 TBIB Medical AI. Tous droits réservés.
                </div>
                <div class="flex space-x-6 text-slate-400">
                    <a href="#" class="hover:text-blue-600 transition-colors"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover:text-blue-600 transition-colors"><i class="fab fa-linkedin"></i></a>
                    <a href="#" class="hover:text-blue-600 transition-colors"><i class="fab fa-github"></i></a>
                </div>
            </div>
        </div>
    </footer>

    @auth
        @if(Auth::user()->isPatient())
            @include('partials.ai-chat')
        @endif
    @endauth
</body>
</html>
