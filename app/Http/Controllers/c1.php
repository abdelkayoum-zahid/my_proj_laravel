<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Notifications\NewUserPendingApproval; // Nous allons créer cette notification
use Illuminate\Support\Facades\Notification;

class c1 extends Controller
{
    //* ===========================
    //* =========== Login =========
    //* ===========================
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Vérifier si le compte est approuvé
            if (!Auth::user()->is_approved) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Votre compte est en attente de validation par l\'administrateur.',
                ]);
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect.',
        ]);
    }

    //* ===========================
    //* ======== Register =========
    //* ===========================
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'email' => 'required|email|unique:users',
            'mot_de_passe' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->nom,
            'email' => $request->email,
            'password' => Hash::make($request->mot_de_passe),
            'role' => 'etudiant', // Rôle par défaut
            'is_approved' => false, // Nouveau compte non approuvé par défaut
        ]);

        // Notifier l'administrateur
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new NewUserPendingApproval($user));
        }

        // On ne connecte pas automatiquement l'utilisateur
        return redirect('/login')->with('status', 'Votre compte a été créé. Il sera activé après validation par l\'administrateur.');
    }

    //* ============================
    //* =========== Logout =========
    //* ============================
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }

    //* ============================
    //* ==== Approbation Admin =====
    //* ============================
    public function approveUser($userId)
    {
        // Seul un admin peut accéder à cette méthode
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $user = User::findOrFail($userId);
        $user->update(['is_approved' => true]);

        // Message de confirmation
        session()->flash('message', 'L\'utilisateur ' . $user->name . ' a été approuvé avec succès.');

        return redirect()->route('dashboard')->with('success', 'Le compte a été approuvé avec succès.');
    }
}