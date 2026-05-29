<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes tâches - {{ auth()->user()->name }}</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
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
            <flux:sidebar.brand href="#" logo="/favicon-32x32.png" logo:dark="/favicon-32x32.png" name="Tâches"
                wire:navigate />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.item icon="calendar" href="{{ route('calendar') }}" wire:navigate>Calendrier
            </flux:sidebar.item>
            <flux:sidebar.item icon="folder" href="{{ route('tasks.index') }}" wire:navigate>Listes des tâches
            </flux:sidebar.item>

            <flux:sidebar.group expandable heading="Favoris" class="grid" icon="bookmark">
                <flux:sidebar.item href="{{ route('construction') }}">Projets</flux:sidebar.item>
                <flux:sidebar.item href="{{ route('construction') }}">Archives</flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:sidebar.spacer />

        <flux:sidebar.nav>
            <flux:sidebar.item icon="cog-6-tooth" href="{{ route('settings') }}" wire:navigate>Paramètres
            </flux:sidebar.item>
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
            <div class="flex justify-between">
                {{-- <flux:heading size="xl" level="1" class="truncate font-bold tracking-tight">
                    {{ $title }}
                </flux:heading> --}}
                <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                    <flux:radio value="light" icon="sun">Light</flux:radio>
                    <flux:radio value="dark" icon="moon">Dark</flux:radio>
                    <flux:radio value="system" icon="computer-desktop">System</flux:radio>
                </flux:radio.group>
            </div>

            {{-- Profil Mobile --}}
            <div class=" flex-1 flex justify-end lg:hidden">
                <flux:dropdown position="top" align="end">
                    {{-- Avatar dynamique basé sur le nom de l'utilisateur connecté --}}
                    <flux:profile
                        avatar="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=2563eb&color=fff" />
                    <flux:menu>
                        <div class="px-4 py-2 text-sm text-zinc-500 border-b border-zinc-100 dark:border-zinc-800">
                            Connecté en tant que <br>
                            <span class="font-bold text-zinc-900 dark:text-white">{{ auth()->user()->name }}</span>
                            <br>
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