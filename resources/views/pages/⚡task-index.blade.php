<?php

use Livewire\Attributes\Layout;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Task;
use Livewire\Component;

new #[Layout('layouts::app', ['title' => 'Liste des tâches'])] class extends Component {
    public Collection $tasks;

    public function mount()
    {
        $this->tasks = Task::all();
    }

    public function destroy(Task $task)
    {
        $task->delete();
        $this->tasks = Task::all();
    }
};

?>

<div class="container flex justify-center mx-auto relative pt-6 px-4">
    <div class="flex flex-col w-full max-w-5xl">

        @if($tasks->isEmpty())
            {{-- État vide (Adapté mode nuit) --}}
            <div
                class="flex flex-col items-center justify-center p-12 bg-white dark:bg-zinc-900/50 border-2 border-dashed border-gray-300 dark:border-zinc-700 rounded-xl shadow-sm transition-colors duration-300">
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-full mb-4">
                    <svg class="w-12 h-12 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-zinc-100">C'est bien calme ici...</h3>
                <p class="text-gray-500 dark:text-zinc-400 mb-8 text-center max-w-sm">
                    Votre liste de tâches est vide. Pourquoi ne pas commencer par planifier votre prochain grand projet ?
                </p>

                <x-link-button href="{{ route('tasks.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-8 py-3 rounded-lg font-bold shadow-lg transform hover:scale-105 transition-all">
                    + Créer ma première tâche
                </x-link-button>
            </div>
        @else
            {{-- En-tête de la table --}}
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-medium text-gray-800 dark:text-zinc-200">Tableau de bord</h2>
                <x-link-button href="{{ route('tasks.create') }}" wire:navigate
                    class="bg-blue-600 dark:bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700 shadow transition-colors">
                    + Ajouter
                </x-link-button>
            </div>

            {{-- Table (Adaptée mode nuit) --}}
            <div
                class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 shadow-sm rounded-xl overflow-hidden transition-colors">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                    <thead class="bg-gray-50 dark:bg-zinc-800/50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                                ID</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                                Tâche</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                                Statut</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-100 dark:divide-zinc-800">
                        @foreach($tasks as $task)
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/40 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-400 dark:text-zinc-500">#{{ $task->id }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-zinc-100">{{ $task->title }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $task->state ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                        {{ $task->state ? 'Terminé' : 'En attente' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-3">
                                    <a href="{{ route('tasks.show', $task->id) }}" wire:navigate
                                        class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40 transition-colors">
                                        Voir
                                    </a>
                                    <a href="{{ route('tasks.edit', $task->id) }}" wire:navigate
                                        class="text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 text-sm transition-colors">Modifier</a>
                                    <button @click="$dispatch('confirm-delete', {{ $task->id }})"
                                        class="group inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 dark:hover:text-red-400 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
    {{-- Modal de Confirmation (Alpine.js) --}}
    <div x-data="{ open: false, taskId: null }" @confirm-delete.window="open = true; taskId = $event.detail"
        x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        {{-- Overlay sombre --}}
        <div class="fixed inset-0 bg-zinc-900/60 backdrop-blur-sm transition-opacity"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div @click.away="open = false"
                class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-zinc-900 p-6 text-left shadow-xl transition-all w-full max-w-sm border border-zinc-200 dark:border-zinc-800">
                <div
                    class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 dark:bg-red-900/30 rounded-full mb-4">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <div class="text-center">
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Supprimer la tâche ?</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2">
                        Êtes-vous sûr ? Cette action ne pourra pas être annulée.
                    </p>
                </div>

                <div class="mt-6 flex flex-col gap-2">
                    <button @click="$wire.destroy(taskId); open = false"
                        class="w-full inline-flex justify-center rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-colors">
                        Oui, supprimer
                    </button>
                    <button @click="open = false"
                        class="w-full inline-flex justify-center rounded-xl bg-white dark:bg-zinc-800 px-4 py-2.5 text-sm font-semibold text-zinc-700 dark:text-zinc-300 border border-zinc-300 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>