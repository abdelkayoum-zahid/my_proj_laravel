<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;    
use App\Models\User;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       // 1. Vérifie si l'utilisateur est authentifié
       if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Veuillez vous connecter');
    }
      if (Auth::user()->role !== 'admin') {
        abort(403, 'Accès réservé aux administrateurs.');
    }
        return $next($request);
    }
}
