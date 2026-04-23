<?php

use App\Models\Task;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.app', ['title' => 'Détails de la tâche'])]
    class extends Component {
    public Task $task;

    public function mount(Task $task)
    {
        $this->task = $task;
    }
};
?>

<div class="max-w-3xl mx-auto space-y-6 pt-6 px-4">
    {{-- En-tête avec bouton retour --}}
    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('tasks.index') }}" wire:navigate
            class="flex items-center text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Retour à la liste
        </a>

        <div class="flex gap-2">
            <x-link-button href="{{ route('tasks.edit', $task->id) }}"
                class="bg-amber-500 text-white px-4 py-2 rounded-lg text-sm">
                Modifier
            </x-link-button>
        </div>
    </div>

    {{-- Carte principale --}}
    <div
        class="bg-white dark:bg-zinc-900 shadow-sm border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
        <div class="p-6 sm:p-8 space-y-8">

            {{-- Section Titre --}}
            <div class="relative">
                <h3 class="text-xs font-bold uppercase tracking-widest text-blue-500 dark:text-blue-400 mb-1">Titre</h3>
                <p class="text-2xl font-bold text-zinc-800 dark:text-white">{{ $task->title }}</p>
            </div>

            {{-- Section Etat (Badge) --}}
            <div class="flex items-center gap-4 p-4 rounded-lg bg-zinc-50 dark:bg-zinc-800/50">
                <div class="shrink-0">
                    @if($task->state)
                        <span
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                    @else
                        <span
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-zinc-200 dark:bg-zinc-700 text-zinc-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">Statut actuel</p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        {{ $task->state ? 'Complétée avec succès' : 'En cours de réalisation' }}
                    </p>
                </div>
            </div>

            {{-- Section Détail --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-widest text-zinc-400 mb-2">Description / Détails</h3>
                <div
                    class="prose dark:prose-invert max-w-none text-zinc-600 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-800/30 p-4 rounded-lg italic">
                    {{ $task->detail ?: 'Aucun détail fourni.' }}
                </div>
            </div>

            {{-- Footer Dates --}}
            <div
                class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-6 border-t border-zinc-100 dark:border-zinc-800 text-xs">
                <div class="flex items-center text-zinc-400">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Créée le {{ $task->created_at->translatedFormat('d F Y') }}
                </div>
                @if($task->updated_at->gt($task->created_at))
                    <div class="flex items-center text-zinc-400">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Modifiée le {{ $task->updated_at->translatedFormat('d F Y') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>