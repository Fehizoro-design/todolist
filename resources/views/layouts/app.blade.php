<!DOCTYPE html>
<html lang="fr" class="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes tâches</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    @livewireStyles
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800 antialiased">
    {{-- 1. Barre latérale (Sidebar) --}}
    <flux:sidebar sticky collapsible="mobile"
        class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.header>
            <flux:sidebar.brand href="#" logo="https://fluxui.dev/img/demo/logo.png"
                logo:dark="https://fluxui.dev/img/demo/dark-mode-logo.png" name="Acme Inc." />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" href="{{ route('tasks.index') }}" wire:navigate>Accueil</flux:sidebar.item>
            <flux:sidebar.item icon="calendar" href="{{ route('calendar') }}" wire:navigate>Calendrier
            </flux:sidebar.item>
            <flux:sidebar.group expandable heading="Favoris" class="grid">
                <flux:sidebar.item href="#">Projets</flux:sidebar.item>
                <flux:sidebar.item href="#">Archives</flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:sidebar.spacer />

        <flux:sidebar.nav>
            <flux:sidebar.item icon="cog-6-tooth" href="#">Paramètres</flux:sidebar.item>
        </flux:sidebar.nav>

        {{-- Profil Desktop (Masqué sur mobile car il passe dans le header) --}}
        <flux:dropdown position="top" align="start" class="max-lg:hidden">
            <flux:sidebar.profile
                avatar="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=2563eb&color=fff"
                name="{{ auth()->user()->name }}" />
            <flux:menu>
                <div class="px-4 py-2 text-sm text-zinc-500 border-b border-zinc-100 dark:border-zinc-800">
                    Connecté en tant que <br>
                    <span class="font-bold text-zinc-900 dark:text-white">{{ auth()->user()->email }}</span>
                </div>

                {{-- Formulaire de déconnexion standard Laravel --}}
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item icon="arrow-right-start-on-rectangle"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Déconnexion
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    {{-- 2. En-tête (Header) avec Titre Centré sur Mobile --}}
    <flux:header class="bg-white lg:bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
        <flux:navbar class="w-full flex items-center px-4 h-16">
            {{-- Bouton Menu Mobile --}}
            <div class="flex-1 lg:hidden">
                <flux:sidebar.toggle icon="bars-2" inset="left" />
            </div>

            {{-- Titre de la page --}}
            <div class="flex-2 lg:flex-1 text-center lg:text-left">
                <flux:heading size="xl" level="1" class="truncate font-bold tracking-tight">
                    {{ $title }}
                </flux:heading>
            </div>

            {{-- Profil Mobile --}}
            <div class="flex-1 flex justify-end lg:hidden">
                <flux:dropdown position="top" align="end">
                    {{-- Avatar dynamique basé sur le nom de l'utilisateur connecté --}}
                    <flux:profile
                        avatar="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=2563eb&color=fff" />
                    <flux:menu>
                        <div class="px-4 py-2 text-sm text-zinc-500 border-b border-zinc-100 dark:border-zinc-800">
                            Connecté en tant que <br>
                            <span class="font-bold text-zinc-900 dark:text-white">{{ auth()->user()->name }}</span> <br>
                            <span class="font-bold text-zinc-900 dark:text-white">{{ auth()->user()->email }}</span>
                        </div>

                        {{-- Formulaire de déconnexion standard Laravel --}}
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item icon="arrow-right-start-on-rectangle"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Déconnexion
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </flux:navbar>
    </flux:header>

    {{-- 3. Contenu Principal --}}
    <flux:main>
        {{ $slot }}
    </flux:main>

    @persist('toast')
    <flux:toast />
    @endpersist

    @livewireScripts
    @fluxScripts
</body>

</html>