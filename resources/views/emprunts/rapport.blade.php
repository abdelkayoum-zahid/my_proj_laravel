@extends('layouts.app')

@section('title', 'Rapport des emprunts')

@section('content')
@if(session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-extrabold text-blue-600 mb-8">Rapport des emprunts</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <!-- Total des emprunts -->
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-100 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total des emprunts</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $statistiques['total'] }}</p>
                </div>
            </div>
        </div>

        <!-- Emprunts en cours -->
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-yellow-100 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">En cours</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $statistiques['en_cours'] }}</p>
                </div>
            </div>
        </div>

        <!-- Emprunts retournés -->
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-green-100 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Retournés</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $statistiques['retournes'] }}</p>
                </div>
            </div>
        </div>

        <!-- Emprunts en retard -->
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-red-100 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">En retard</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $statistiques['en_retard'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Livres les plus empruntés -->
    <div class="bg-white rounded-2xl shadow-xl p-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Livres les plus empruntés</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Titre</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Auteur</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Nombre d'emprunts</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Disponibilité</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($statistiques['livres_populaires'] as $livre)
                    <tr class="hover:bg-blue-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $livre->titre }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $livre->auteur }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $livre->emprunts_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $livre->disponible ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $livre->disponible ? 'Disponible' : 'Indisponible' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection