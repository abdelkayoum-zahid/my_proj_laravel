@extends('layouts.app')

@section('title', 'Mes emprunts')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-extrabold text-blue-600">Mes emprunts</h1>
        <a href="{{ route('livres.index') }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition font-semibold">+ Emprunter un livre</a>
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

    <div class="overflow-x-auto bg-white rounded-2xl shadow-2xl">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Livre</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Date d'emprunt</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Date de retour prévue</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($emprunts as $emprunt)
                    <tr class="hover:bg-blue-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ $emprunt->livre->titre }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ date('d/m/Y', strtotime($emprunt->date_emprunt)) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ date('d/m/Y', strtotime($emprunt->date_retour_prevue)) }}</td>
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
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('emprunts.show', $emprunt) }}" class="inline-block bg-emerald-500 text-white px-3 py-1 rounded-lg shadow hover:bg-emerald-600 transition text-xs font-semibold" title="Voir">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-400">Vous n'avez aucun emprunt</td>
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