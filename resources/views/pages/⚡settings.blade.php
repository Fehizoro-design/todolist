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
    <div>

    </div>
    {{-- Ajouter d'autres sections de paramètres selon les besoins --}}
</div>