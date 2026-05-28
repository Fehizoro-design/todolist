<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app', ['title' => 'Oups !'])]
    class extends Component {
    //
};
?>

<div>
    {{-- Do what you can, with what you have, where you are. - Theodore Roosevelt --}}
    <div class="flex flex-col items-center justify-center min-h-screen bg-zinc-50 dark:bg-zinc-950 p-4">
        <h1
            class="text-6xl font-extrabold text-transparent bg-linear-to-r from-zinc-900 to-zinc-600 dark:from-white dark:to-zinc-400 bg-clip-text mb-4">
            404
        </h1>
        <p class="text-xl text-gray-600 dark:text-gray-400 mb-8">Cette page est encore en construction.</p>
        <x-link-button href="{{ route('tasks.index') }}"
            class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-bold shadow-lg transform hover:scale-105 transition-all">
            Retour à l'accueil
        </x-link-button>
    </div>