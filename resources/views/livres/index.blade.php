@extends('layouts.app')

@section('title', 'Catalogue des livres')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-extrabold text-blue-600">{{ auth()->user()->role === 'etudiant' ? 'Catalogue des livres' : 'Gestion des livres' }}</h1>
        @if(in_array(auth()->user()->role, ['admin', 'gestionnaire']))
        <a href="{{ route('livres.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition font-semibold">+ Ajouter un livre</a>
        @endif
    </div>

    @if(session('success'))
        <div class="relative mb-6 p-4 rounded-lg bg-emerald-100 text-emerald-800 font-semibold shadow">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-emerald-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
            <button onclick="this.parentElement.remove()" class="absolute top-4 right-4 text-emerald-600 hover:text-emerald-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    <!-- Recherche -->
    <div class="bg-white p-6 rounded-2xl shadow-xl mb-8">
        <form action="{{ route('livres.index') }}" method="GET" class="flex gap-4">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Rechercher un livre..." 
                   class="flex-1 border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition font-semibold">
                Rechercher
            </button>
        </form>
    </div>

    <div class="overflow-x-auto bg-white rounded-2xl shadow-2xl">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Titre</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Auteur</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Année</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Catégorie</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($livres as $livre)
                    <tr class="hover:bg-blue-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ $livre->titre }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ $livre->auteur }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ $livre->annee }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ $livre->categorie }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $livre->disponible ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $livre->disponible ? 'Disponible' : 'Emprunté' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap flex flex-wrap gap-2">
                            <!-- Détails visibles par tous -->
                            <a href="{{ route('livres.show', $livre) }}" class="inline-block bg-emerald-500 text-white px-3 py-1 rounded-lg shadow hover:bg-emerald-600 transition text-xs font-semibold" title="Voir">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>

                            @if(auth()->user()->role === 'etudiant' && $livre->disponible)
                            <!-- Bouton d'emprunt pour les étudiants si le livre est disponible -->
                            <form action="{{ route('emprunts.store') }}" method="POST" class="inline-block">
                                @csrf
                                <input type="hidden" name="livre_id" value="{{ $livre->id }}">
                                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-lg shadow hover:bg-blue-700 transition text-xs font-semibold" title="Emprunter">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </form>
                            @endif

                            @if(in_array(auth()->user()->role, ['admin', 'gestionnaire']))
                            <!-- Actions pour les gestionnaires et admin -->
                            <a href="{{ route('livres.edit', $livre) }}" class="inline-block bg-blue-600 text-white px-3 py-1 rounded-lg shadow hover:bg-blue-700 transition text-xs font-semibold" title="Modifier">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a4 4 0 01-2.828 1.172H7v-2a4 4 0 011.172-2.828z"/>
                                </svg>
                            </a>

                            <form action="{{ route('livres.destroy', $livre) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg shadow hover:bg-red-600 transition text-xs font-semibold" title="Supprimer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8"/>
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-400">Aucun livre trouvé</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $livres->appends(request()->query())->links() }}
    </div>
</div>
@endsection