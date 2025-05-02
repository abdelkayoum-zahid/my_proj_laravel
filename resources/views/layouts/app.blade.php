<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogiGestion - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen">
    <!-- Header Principal -->


    <div class="flex min-h-[calc(100vh-4rem)]">
        <!-- Sidebar -->
        @auth
        <aside class="w-64 bg-white shadow-2xl flex flex-col py-8 px-6">
          <div class="mt-auto pt-8 mb-8">
            @auth
                <div class="mb-2 text-sm text-gray-500">Connecté en tant que <br> <span class="font-bold text-blue-700">{{ auth()->user()->name }}</span></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="w-full text-left text-sm text-red-600 hover:underline" type="submit">Se déconnecter</button>
                </form>
            @endauth
        </div>
        <hr class="border-t mb-8">

            <nav class="space-y-2 flex-1">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-lg font-semibold transition
                    {{ Request::is('dashboard') ? 'bg-blue-600 text-white border-l-4 border-blue-700 shadow' : 'text-gray-800 bg-gray-50 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Tableau de bord
                </a>

                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 rounded-lg font-semibold transition
                    {{ Request::is('profile*') ? 'bg-blue-600 text-white border-l-4 border-blue-700 shadow' : 'text-gray-800 bg-gray-50 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Mon Profil
                </a>

                <a href="{{ route('livres.index') }}" class="flex items-center px-4 py-3 rounded-lg font-semibold transition
                    {{ Request::is('livres*') ? 'bg-blue-600 text-white border-l-4 border-blue-700 shadow' : 'text-gray-800 bg-gray-50 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Livres
                </a>

                <a href="{{ route('emprunts.mes-emprunts') }}" class="flex items-center px-4 py-3 rounded-lg font-semibold transition
                    {{ Request::is('mes-emprunts*') ? 'bg-blue-600 text-white border-l-4 border-blue-700 shadow' : 'text-gray-800 bg-gray-50 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Mes Emprunts
                </a>

                @can('gestionnaire')
                <a href="{{ route('emprunts.index') }}" class="flex items-center px-4 py-3 rounded-lg font-semibold transition
                    {{ Request::is('emprunts*') && !Request::is('mes-emprunts*') ? 'bg-blue-600 text-white border-l-4 border-blue-700 shadow' : 'text-gray-800 bg-gray-50 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Gestion Emprunts
                </a>
                @endcan

                @can('admin')
                <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 rounded-lg font-semibold transition
                    {{ Request::is('users*') ? 'bg-blue-600 text-white border-l-4 border-blue-700 shadow' : 'text-gray-800 bg-gray-50 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Utilisateurs
                </a>
                @endcan
            </nav>

            
        </aside>
        @endauth

        <!-- Content -->
        <div class="flex-1 flex flex-col min-h-screen">
          <header class="bg-white shadow flex items-center h-16 px-8">
            <div class="container mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                  <div class="flex-1">
                    <span class="text-2xl font-extrabold text-blue-600">biblio<span class="text-gray-600">App</span></span>
                </div>
                    @guest
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600">Connexion</a>
                            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Inscription</a>
                        </div>
                    @endguest
                </div>
            </div>
        </header>
        <main class="flex-1 p-8">
          
            @yield('content')
        </main>
        </div>
    </div>
</body>
</html>
