@extends('layouts.app')

@section('title', 'Modifier le livre')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-2xl p-8">
        <h1 class="text-3xl font-extrabold text-blue-600 mb-8">Modifier le livre</h1>
        
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800 font-semibold shadow">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('livres.update', $livre) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="titre" class="block mb-2 text-sm font-semibold text-indigo-700">Titre</label>
                <input type="text" name="titre" id="titre" value="{{ old('titre', $livre->titre) }}"
                       class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition"
                       required>
                @error('titre') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="auteur" class="block mb-2 text-sm font-semibold text-indigo-700">Auteur</label>
                <input type="text" name="auteur" id="auteur" value="{{ old('auteur', $livre->auteur) }}"
                       class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition"
                       required>
                @error('auteur') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="annee" class="block mb-2 text-sm font-semibold text-indigo-700">Année de publication</label>
                <input type="number" name="annee" id="annee" value="{{ old('annee', $livre->annee) }}"
                       min="1000" max="{{ date('Y') + 1 }}"
                       class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition"
                       required>
                @error('annee') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="categorie" class="block mb-2 text-sm font-semibold text-indigo-700">Catégorie</label>
                <input type="text" 
                       name="categorie" 
                       id="categorie" 
                       value="{{ old('categorie', $livre->categorie) }}"
                       class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition"
                       required>
                @error('categorie') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mt-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="disponible" id="disponible" value="1"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-2 focus:ring-indigo-200 transition"
                           {{ old('disponible', $livre->disponible) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-indigo-700 font-semibold">Disponible pour l'emprunt</span>
                </label>
            </div>

            <div class="flex gap-4 mt-8">
                <button class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition font-semibold">Mettre à jour</button>
                <a href="{{ route('livres.index') }}" class="bg-gray-100 text-gray-600 px-5 py-2 rounded-lg shadow hover:bg-gray-200 transition font-semibold">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection