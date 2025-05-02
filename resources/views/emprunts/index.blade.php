@extends('layouts.app')

@section('title', 'Gestion des emprunts')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-extrabold text-blue-600">Gestion des emprunts</h1>
        @if(auth()->user()->role === 'etudiant')
        <a href="{{ route('emprunts.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition font-semibold">+ Nouvel emprunt</a>
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

    <!-- Filtres pour gestionnaires et admin -->
    @if(in_array(auth()->user()->role, ['admin', 'gestionnaire']))
    <div class="bg-white p-6 rounded-2xl shadow-xl mb-8">
        <form action="{{ route('emprunts.index') }}" method="GET" class="flex gap-4">
            <select name="statut" class="flex-1 border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                <option value="">Tous les statuts</option>
                <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                <option value="retourne" {{ request('statut') == 'retourne' ? 'selected' : '' }}>Retourné</option>
                <option value="en_retard" {{ request('statut') == 'en_retard' ? 'selected' : '' }}>En retard</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition font-semibold">
                Filtrer
            </button>
        </form>
    </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-2xl shadow-2xl">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Livre</th>
                    @if(in_array(auth()->user()->role, ['admin', 'gestionnaire']))
                    <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Emprunteur</th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Date d'emprunt</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Date de retour</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($emprunts as $emprunt)
                    <tr class="hover:bg-blue-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ $emprunt->livre->titre }}</td>
                        @if(in_array(auth()->user()->role, ['admin', 'gestionnaire']))
                        <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ $emprunt->user->name }}</td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ date('d/m/Y', strtotime($emprunt->date_emprunt)) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-600">
                            {{ $emprunt->date_retour ? date('d/m/Y', strtotime($emprunt->date_retour)) : 'En cours' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statut_class = 'bg-yellow-100 text-yellow-700';
                                $statut_text = 'En cours';
                                
                                if ($emprunt->date_retour) {
                                    $statut_class = 'bg-green-100 text-green-700';
                                    $statut_text = 'Retourné';
                                } elseif (strtotime($emprunt->date_retour_prevue) < strtotime('today')) {
                                    $statut_class = 'bg-red-100 text-red-700';
                                    $statut_text = 'En retard';
                                }
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statut_class }}">
                                {{ $statut_text }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap flex flex-wrap gap-2">
                            <!-- Détails visibles par tous -->
                            <a href="{{ route('emprunts.show', $emprunt) }}" class="inline-block bg-emerald-500 text-white px-3 py-1 rounded-lg shadow hover:bg-emerald-600 transition text-xs font-semibold" title="Voir">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>

                            @if(in_array(auth()->user()->role, ['admin', 'gestionnaire']))
                            <!-- Actions pour les gestionnaires et admin -->
                            <a href="{{ route('emprunts.edit', $emprunt) }}" class="inline-block bg-blue-600 text-white px-3 py-1 rounded-lg shadow hover:bg-blue-700 transition text-xs font-semibold" title="Modifier">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a4 4 0 01-2.828 1.172H7v-2a4 4 0 011.172-2.828z"/>
                                </svg>
                            </a>

                            @if(!$emprunt->date_retour)
                            <form action="{{ route('emprunts.retourner', $emprunt) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded-lg shadow hover:bg-green-600 transition text-xs font-semibold" title="Marquer comme retourné">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </form>
                            @endif

                            <form action="{{ route('emprunts.destroy', $emprunt) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet emprunt ?');">
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
                        <td colspan="6" class="px-6 py-4 text-center text-gray-400">Aucun emprunt trouvé</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $emprunts->links() }}
    </div>
</div>
@endsection