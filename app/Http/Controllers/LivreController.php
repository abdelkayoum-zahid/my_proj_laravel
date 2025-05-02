<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class LivreController extends BaseController
{
    public function __construct()
    {
        // Les méthodes index et show sont accessibles à tous les utilisateurs authentifiés
        $this->middleware('auth');
        
        // Les autres méthodes nécessitent les privilèges de gestionnaire
        $this->middleware('gestionnaire')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Livre::query();

        // Recherche par titre ou auteur
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('auteur', 'like', "%{$search}%");
            });
        }

        $livres = $query->latest()->paginate(10);

        return view('livres.index', compact('livres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('livres.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'annee' => 'required|integer|min:1000|max:' . (date('Y') + 1),
            'categorie' => 'required|string|max:255',
            'disponible' => 'boolean',
        ]);

        $livre = Livre::create($validated);

        return redirect()->route('livres.index')
            ->with('success', 'Le livre a été ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Livre $livre)
    {
        return view('livres.show', compact('livre'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Livre $livre)
    {
        return view('livres.edit', compact('livre'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Livre $livre)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'annee' => 'required|integer|min:1000|max:' . (date('Y') + 1),
            'categorie' => 'required|string|max:255',
            'disponible' => 'boolean',
        ]);

        $livre->update($validated);

        return redirect()->route('livres.index')
            ->with('success', 'Le livre a été mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Livre $livre)
    {
        $livre->delete();

        return redirect()->route('livres.index')
            ->with('success', 'Le livre a été supprimé avec succès.');
    }
}
