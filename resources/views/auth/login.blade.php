@extends('layouts.app')

@section('title', 'se connecter')
@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh]">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-center">Connexion</h2>
        <form method="POST" action="/login" class="space-y-4">
            @csrf
            <input name="email" type="email" required placeholder="Email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            <input name="password" type="password" required placeholder="Mot de passe" value="{{ old('password') }}" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 font-semibold">Se connecter</button>
            @if ($errors->has('email'))
    <div class="text-red-500 text-sm mt-4 text-center">
        {{ $errors->first('email') }}
    </div>
@endif
        </form>
        
    </div>
</div>
@endsection