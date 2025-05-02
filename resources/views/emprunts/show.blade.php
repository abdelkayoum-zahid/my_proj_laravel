@extends('layouts.app')

@section('title', 'Détails de l\'emprunt')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-xl mx-auto bg-white rounded-2xl shadow-2xl p-8">
        <h1 class="text-3xl font-extrabold text-blue-600 mb-8">Détails de l'emprunt</h1>
        
        <div class="grid grid-cols-1 gap-4 text-slate-800 mb-8">
            <div class="border-b pb-4">
                <h2 class="font-bold text-lg mb-2">Livre</h2>
                <div class="text-slate-600">{{ $emprunt->livre->titre }}</div>
                <div class="text-sm text-slate-500">par {{ $emprunt->livre->auteur }}</div>
            </div>

            @if(in_array(auth()->user()->role, ['admin', 'gestionnaire']))
            <div class="border-b pb-4">
                <h2 class="font-bold text-lg mb-2">Emprunteur</h2>
                <div class="text-slate-600">{{ $emprunt->user->name }}</div>
                <div class="text-sm text-slate-500">{{ $emprunt->user->email }}</div>
            </div>
            @endif

            <div class="border-b pb-4">
                <h2 class="font-bold text-lg mb-2">Dates</h2>
                <div class="flex flex-col gap-2">
                    <div>
                        <span class="text-slate-500">Date d'emprunt:</span>
                        <span class="text-slate-600">{{ date('d/m/Y', strtotime($emprunt->date_emprunt)) }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500">Date de retour:</span>
                        <span class="text-slate-600">
                            @if($emprunt->date_retour)
                                {{ date('d/m/Y', strtotime($emprunt->date_retour)) }}
                            @else
                                <span class="text-yellow-600">En cours</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="font-bold text-lg mb-2">Statut</h2>
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
            </div>
        </div>

        <div class="flex items-center gap-4">
            @if(in_array(auth()->user()->role, ['admin', 'gestionnaire']))
                <a href="{{ route('emprunts.edit', $emprunt) }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition font-semibold">Modifier</a>
                
                @if(!$emprunt->date_retour)
                <form action="{{ route('emprunts.retourner', $emprunt) }}" method="POST" class="inline-block">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="bg-green-500 text-white px-5 py-2 rounded-lg shadow hover:bg-green-600 transition font-semibold">
                        Marquer comme retourné
                    </button>
                </form>
                @endif

                <form action="{{ route('emprunts.destroy', $emprunt) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet emprunt ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-5 py-2 rounded-lg shadow hover:bg-red-600 transition font-semibold">Supprimer</button>
                </form>
            @endif
            
            <a href="{{ auth()->user()->role === 'etudiant' ? route('emprunts.mes-emprunts') : route('emprunts.index') }}" 
               class="bg-gray-100 text-gray-600 px-5 py-2 rounded-lg shadow hover:bg-gray-200 transition font-semibold ml-auto">
                ← Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection