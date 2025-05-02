@extends('layouts.app')

@section('title', 'Modifier l\'emprunt')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-2xl p-8">
        <h1 class="text-3xl font-extrabold text-blue-600 mb-8">Modifier l'emprunt</h1>
        
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800 font-semibold shadow">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('emprunts.update', $emprunt) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="livre_id" class="block mb-2 text-sm font-semibold text-indigo-700">Livre</label>
                <select name="livre_id" id="livre_id" class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition" required>
                    <option value="">Sélectionnez un livre</option>
                    @foreach($livres as $livre)
                        <option value="{{ $livre->id }}" {{ old('livre_id', $emprunt->livre_id) == $livre->id ? 'selected' : '' }}>
                            {{ $livre->titre }} ({{ $livre->auteur }})
                        </option>
                    @endforeach
                </select>
                @error('livre_id') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="user_id" class="block mb-2 text-sm font-semibold text-indigo-700">Emprunteur</label>
                <select name="user_id" id="user_id" class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition" required>
                    <option value="">Sélectionnez un emprunteur</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $emprunt->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_id') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="date_emprunt" class="block mb-2 text-sm font-semibold text-indigo-700">Date d'emprunt</label>
                <input type="date" name="date_emprunt" id="date_emprunt" 
                       value="{{ old('date_emprunt', $emprunt->date_emprunt) }}"
                       class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition"
                       required>
                @error('date_emprunt') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="date_retour" class="block mb-2 text-sm font-semibold text-indigo-700">Date de retour</label>
                <input type="date" name="date_retour" id="date_retour" 
                       value="{{ old('date_retour', $emprunt->date_retour) }}"
                       class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition">
                @error('date_retour') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="flex gap-4 mt-8">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition font-semibold">
                    Mettre à jour
                </button>
                <a href="{{ route('emprunts.index') }}" class="bg-gray-100 text-gray-600 px-5 py-2 rounded-lg shadow hover:bg-gray-200 transition font-semibold">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection