<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Livre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Routing\Controller as BaseController;

class EmpruntController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('gestionnaire')->only(['index', 'edit', 'update', 'destroy', 'valider', 'refuser', 'retourner', 'rapport']);
    }

    public function index(Request $request)
    {
        $query = Emprunt::with(['user', 'livre']);

        // Filtre par statut
        if ($request->has('statut')) {
            switch ($request->statut) {
                case 'en_cours':
                    $query->whereNull('date_retour');
                    break;
                case 'retourne':
                    $query->whereNotNull('date_retour');
                    break;
                case 'en_retard':
                    $query->whereNull('date_retour')
                         ->whereDate('date_retour_prevue', '<', now());
                    break;
            }
        }

        $emprunts = $query->orderBy('date_emprunt', 'desc')->paginate(10);
        return view('emprunts.index', compact('emprunts'));
    }

    public function mesEmprunts()
    {
        $emprunts = Emprunt::with(['livre'])
            ->where('user_id', Auth::id())
            ->orderBy('date_emprunt', 'desc')
            ->paginate(10);
        return view('emprunts.mes-emprunts', compact('emprunts'));
    }

    public function create()
    {
        $livres = Livre::where('disponible', true)->get();
        return view('emprunts.create', compact('livres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'livre_id' => 'required|exists:livres,id',
            'date_emprunt' => 'required|date',
        ]);

        $livre = Livre::findOrFail($validated['livre_id']);
        if (!$livre->disponible) {
            return back()->withErrors(['livre_id' => 'Ce livre n\'est pas disponible.']);
        }

        // Calculer la date de retour prévue (15 jours par défaut)
        $date_retour_prevue = Carbon::parse($validated['date_emprunt'])->addDays(15);

        $emprunt = new Emprunt($validated);
        $emprunt->user_id = Auth::id();
        $emprunt->date_retour_prevue = $date_retour_prevue;
        $emprunt->statut = 'en_cours';
        $emprunt->save();

        $livre->update(['disponible' => false]);

        return redirect()->route('emprunts.mes-emprunts')
            ->with('success', 'Votre demande d\'emprunt a été enregistrée avec succès.');
    }

    public function show(Emprunt $emprunt)
    {
        if (!in_array(Auth::user()->role, ['admin', 'gestionnaire']) && $emprunt->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à voir cet emprunt.');
        }

        $emprunt->load(['user', 'livre']);
        return view('emprunts.show', compact('emprunt'));
    }

    public function edit(Emprunt $emprunt)
    {
        $livres = Livre::all();
        $users = User::all();
        return view('emprunts.edit', compact('emprunt', 'livres', 'users'));
    }

    public function update(Request $request, Emprunt $emprunt)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'livre_id' => 'required|exists:livres,id',
            'date_emprunt' => 'required|date',
            'date_retour_prevue' => 'required|date|after:date_emprunt',
            'date_retour' => 'nullable|date|after:date_emprunt',
        ]);

        if ($validated['livre_id'] !== $emprunt->livre_id) {
            $ancien_livre = $emprunt->livre;
            $nouveau_livre = Livre::findOrFail($validated['livre_id']);
            
            if (!$nouveau_livre->disponible && $nouveau_livre->id !== $emprunt->livre_id) {
                return back()->withErrors(['livre_id' => 'Ce livre n\'est pas disponible.']);
            }

            $ancien_livre->update(['disponible' => true]);
            $nouveau_livre->update(['disponible' => false]);
        }

        // Mise à jour du statut
        if (isset($validated['date_retour'])) {
            $validated['statut'] = 'retourné';
            $emprunt->livre->update(['disponible' => true]);
        } elseif (Carbon::parse($validated['date_retour_prevue'])->isPast()) {
            $validated['statut'] = 'en_retard';
        } else {
            $validated['statut'] = 'en_cours';
        }

        $emprunt->update($validated);

        return redirect()->route('emprunts.index')
            ->with('success', 'L\'emprunt a été mis à jour avec succès.');
    }

    public function destroy(Emprunt $emprunt)
    {
        $emprunt->livre->update(['disponible' => true]);
        $emprunt->delete();

        return redirect()->route('emprunts.index')
            ->with('success', 'L\'emprunt a été supprimé avec succès.');
    }

    public function valider(Emprunt $emprunt)
    {
        $emprunt->update(['statut' => 'validé']);
        return redirect()->back()->with('success', 'L\'emprunt a été validé.');
    }

    public function refuser(Emprunt $emprunt)
    {
        $emprunt->livre->update(['disponible' => true]);
        $emprunt->update(['statut' => 'refusé']);
        return redirect()->back()->with('success', 'L\'emprunt a été refusé.');
    }

    public function retourner(Emprunt $emprunt)
    {
        $emprunt->update([
            'date_retour' => now(),
            'statut' => 'retourné'
        ]);
        $emprunt->livre->update(['disponible' => true]);
        return redirect()->back()->with('success', 'Le retour a été enregistré.');
    }

    public function rapport()
    {
        $statistiques = [
            'total' => Emprunt::count(),
            'en_cours' => Emprunt::whereNull('date_retour')->count(),
            'retournes' => Emprunt::whereNotNull('date_retour')->count(),
            'en_retard' => Emprunt::whereNull('date_retour')
                ->whereDate('date_retour_prevue', '<', now())
                ->count(),
            'livres_populaires' => Livre::withCount('emprunts')
                ->orderBy('emprunts_count', 'desc')
                ->limit(5)
                ->get(),
        ];

        return view('emprunts.rapport', compact('statistiques'));
    }
}
