<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AccountStatusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user) {
            // Check if user account is active
            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->withErrors(['email' => 'Votre compte a été désactivé par l\'administrateur.']);
            }

            // Check if doctor account is validated
            if ($user->role === 'doctor' && !$user->doctor->is_validated) {
                // Allow access to profile but restrict other doctor actions
                // Or just restrict everything except dashboard with a warning
                if (!$request->routeIs('dashboard') && !$request->routeIs('doctor.profile') && !$request->routeIs('doctor.profile.update')) {
                    return redirect()->route('dashboard')->with('error', 'Votre compte médecin est en attente de validation par l\'administrateur.');
                }
            }
        }

        return $next($request);
    }
}
