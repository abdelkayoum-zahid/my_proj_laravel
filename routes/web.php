<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\c1;
use App\Http\Controllers\LivreController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmpruntController;

// Routes publiques
Route::get('/', function () {
    return view('welcome');
});

// Routes d'authentification
Route::get('/login', [c1::class, 'showLogin'])->name('login');
Route::post('/login', [c1::class, 'login']);
Route::get('/register', [c1::class, 'showRegister'])->name('register');
Route::post('/register', [c1::class, 'register']);
Route::post('/logout', [c1::class, 'logout'])->name('logout');

// Routes pour tous les utilisateurs authentifiés (étudiants inclus)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/livres', [LivreController::class, 'index'])->name('livres.index');
    Route::get('/livres/{livre}', [LivreController::class, 'show'])->name('livres.show');
    
    // Routes du profil utilisateur
    Route::get('/profile', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    
    // Routes pour les emprunts (étudiants)
    Route::get('/mes-emprunts', [EmpruntController::class, 'mesEmprunts'])->name('emprunts.mes-emprunts');
    Route::get('/emprunts/create', [EmpruntController::class, 'create'])->name('emprunts.create');
    Route::post('/emprunts', [EmpruntController::class, 'store'])->name('emprunts.store');
    Route::get('/emprunts/{emprunt}', [EmpruntController::class, 'show'])->name('emprunts.show');
});

// Routes pour les gestionnaires et admin
Route::middleware(['gestionnaire'])->group(function () {
    // Gestion des livres
    Route::resource('livres', LivreController::class)->except(['index', 'show']);
    
    // Gestion des emprunts
    Route::get('/emprunts', [EmpruntController::class, 'index'])->name('emprunts.index');
    Route::get('/emprunts/{emprunt}/edit', [EmpruntController::class, 'edit'])->name('emprunts.edit');
    Route::put('/emprunts/{emprunt}', [EmpruntController::class, 'update'])->name('emprunts.update');
    Route::delete('/emprunts/{emprunt}', [EmpruntController::class, 'destroy'])->name('emprunts.destroy');
    Route::put('/emprunts/{emprunt}/valider', [EmpruntController::class, 'valider'])->name('emprunts.valider');
    Route::put('/emprunts/{emprunt}/refuser', [EmpruntController::class, 'refuser'])->name('emprunts.refuser');
    Route::put('/emprunts/{emprunt}/retourner', [EmpruntController::class, 'retourner'])->name('emprunts.retourner');
    
    // Rapports
    Route::get('/rapports/emprunts', [EmpruntController::class, 'rapport'])->name('rapports.emprunts');
});

// Routes pour admin uniquement
Route::middleware(['admin'])->group(function () {
    Route::resource('users', UserController::class)->except(['show']);
    Route::post('/admin/approve-user/{user}', [c1::class, 'approveUser'])->name('admin.approve.user');
    Route::get('/parametres', [DashboardController::class, 'parametres'])->name('admin.parametres');
});


