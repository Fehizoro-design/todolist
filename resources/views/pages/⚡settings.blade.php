<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts::app', ['title' => 'Paramètres'])] class extends Component {
    //
};
?>

<div>
    <h1 class="text-2xl font-bold mb-4">Paramètres de l'application</h1>
    <p class="text-gray-600 dark:text-gray-400">Ici, vous pouvez ajuster les paramètres de votre application.</p>

    {{-- Exemple de section de paramètres --}}
    <div class="mt-6">
        <h2 class="text-xl font-semibold mb-2">Thème</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-4">Choisissez entre le mode clair et sombre.</p>
        <flux:switch x-data x-model="$flux.dark" label="Mode sombre" />
    </div>
    {{-- Ajouter d'autres sections de paramètres selon les besoins --}}
</div>