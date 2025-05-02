<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class GestionnaireMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter');
        }

        if (!in_array(Auth::user()->role, ['admin', 'gestionnaire'])) {
            abort(403, 'Accès réservé aux gestionnaires et administrateurs.');
        }

        return $next($request);
    }
}