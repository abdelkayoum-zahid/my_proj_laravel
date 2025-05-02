@extends('layouts.app')

@section('title', 'Créer un compte')
@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh]">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-center">Inscription</h2>
        <form method="POST" action="/register" class="space-y-4">
            @csrf
            <input name="nom" placeholder="Nom" value="{{ old('nom') }}" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            @error('nom')
                <style>
                    input[name="nom"] {
                        border-color: red;
                    }
                </style>
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
            <input name="email" type="email" placeholder="Email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            @error('email')
                <style>
                    input[name="email"] {
                        border-color: red;
                    }
                </style>
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
            <input name="mot_de_passe" type="password" placeholder="Mot de passe" value="{{ old('mot_de_passe') }}" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            @error('mot_de_passe')
                <style>
                    input[name="mot_de_passe"] {
                        border-color: red;
                    }
                </style>
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
            <input name="mot_de_passe_confirmation" type="password" placeholder="Confirmer" value="{{ old('mot_de_passe_confirmation') }}" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            @error('mot_de_passe_confirmation')
                <style>
                    input[name="mot_de_passe_confirmation"] {
                        border-color: red;
                    }
                </style>
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 font-semibold">S'inscrire</button>
        </form>
    </div>
</div>
@endsection