@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-2xl p-8">
        <h1 class="text-3xl font-extrabold text-blue-600 mb-8">Modifier mon profil</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800 font-semibold shadow">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block mb-2 text-sm font-semibold text-indigo-700">Nom</label>
                <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}"
                       class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition"
                       required>
                @error('name') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="email" class="block mb-2 text-sm font-semibold text-indigo-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}"
                       class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition"
                       required>
                @error('email') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="password" class="block mb-2 text-sm font-semibold text-indigo-700">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                <input type="password" name="password" id="password"
                       class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition">
                @error('password') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block mb-2 text-sm font-semibold text-indigo-700">Confirmer le nouveau mot de passe</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition">
            </div>

            <div class="flex gap-4 mt-8">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition font-semibold">
                    Mettre à jour mon profil
                </button>
                <a href="{{ route('dashboard') }}" class="bg-gray-100 text-gray-600 px-5 py-2 rounded-lg shadow hover:bg-gray-200 transition font-semibold">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection