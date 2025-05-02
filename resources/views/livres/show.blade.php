@extends('layouts.app')

@section('title', 'Détails du livre')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-xl mx-auto bg-white rounded-2xl shadow-2xl p-8">
        <h1 class="text-3xl font-extrabold text-blue-600 mb-8">Détails du livre</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-slate-800 mb-8">
            <div><strong>Titre :</strong> {{ $livre->titre }}</div>
            <div><strong>Auteur :</strong> {{ $livre->auteur }}</div>
            <div><strong>Année :</strong> {{ $livre->annee }}</div>
            <div><strong>Catégorie :</strong> {{ $livre->categorie }}</div>
            <div><strong>Statut :</strong> <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $livre->disponible ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $livre->disponible ? 'Disponible' : 'Emprunté' }}</span></div>
        </div>

        @if($livre->emprunts->isNotEmpty())
            <div class="mt-8 mb-8">
                <h2 class="text-xl font-semibold text-slate-800 mb-4">Historique des emprunts</h2>
                <div class="space-y-3">
                    @foreach($livre->emprunts->sortByDesc('date_emprunt') as $emprunt)
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="flex justify-between text-sm">
                                <span class="font-medium">{{ $emprunt->user->name }}</span>
                                <span class="text-gray-600">
                                    Du {{ date('d/m/Y', strtotime($emprunt->date_emprunt)) }}
                                    @if($emprunt->date_retour)
                                        au {{ date('d/m/Y', strtotime($emprunt->date_retour)) }}
                                    @else
                                        (En cours)
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="flex flex-wrap gap-4 mt-8">
            <a href="{{ route('livres.edit', $livre) }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition font-semibold">Modifier</a>
            <form action="{{ route('livres.destroy', $livre) }}" method="POST" onsubmit="return confirm('Supprimer ce livre ?');" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-5 py-2 rounded-lg shadow hover:bg-red-600 transition font-semibold">Supprimer</button>
            </form>
            <a href="{{ route('livres.index') }}" class="bg-gray-100 text-gray-600 px-5 py-2 rounded-lg shadow hover:bg-gray-200 transition font-semibold ml-auto">← Retour à la liste</a>
        </div>
    </div>
</div>
@endsection