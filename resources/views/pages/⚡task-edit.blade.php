<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\Task;

new #[Layout('layouts::app', ['title' => 'Modifier une tâche'])]
    class extends Component {
    public Task $task;

    #[Validate('required|max:100')]
    public string $title = '';

    #[Validate('required|max:500')]
    public string $detail = '';

    public bool $state = false;

    public string $messageOk = '';

    public function mount(Task $task)
    {
        $this->task = $task;
        $this->fill($this->task);
    }

    public function save()
    {
        $this->validate();
        $this->task->title = $this->title;
        $this->task->detail = $this->detail;
        $this->task->state = $this->state;
        $this->task->save();
        Flux::toast(
            variant: 'success',
            heading: 'Modification enregistrée',
            text: 'La tâche a été mise à jour avec succès.'
        );
        return $this->redirectRoute('tasks.index', navigate: true);
    }
}
?>

<div class="max-w-3xl mx-auto pt-6 px-4">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-zinc-800 dark:text-white">Modifier la tâche</h2>
            <p class="text-zinc-500 dark:text-zinc-400 text-sm">Mettez à jour les informations de votre activité.</p>
        </div>
        <a href="{{ route('tasks.index') }}" wire:navigate
            class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 transition-colors">
            Annuler
        </a>
    </div>

    <form wire:submit="save" class="space-y-6">
        @csrf
        <div
            class="bg-white dark:bg-zinc-900 shadow-sm border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 sm:p-8 space-y-6">

            <div class="space-y-2">
                <label for="title" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                    Titre de la tâche
                </label>
                <input type="text" id="title" wire:model="title"
                    class="w-full px-4 py-2.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                    placeholder="Ex: Acheter du pain...">
                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <label for="detail" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                    Description détaillée
                </label>
                <textarea id="detail" wire:model="detail" rows="4"
                    class="w-full px-4 py-2.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                    placeholder="Ajoutez des notes ici..."></textarea>
                @error('detail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div
                class="flex items-center justify-between p-4 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg border border-zinc-100 dark:border-zinc-800">
                <div class="flex flex-col">
                    <span class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">Marquer comme terminée</span>
                    <span class="text-xs text-zinc-500 dark:text-zinc-400">Cochez cette case si le travail est
                        fini.</span>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="state" class="sr-only peer">
                    <div
                        class="w-11 h-6 bg-zinc-300 peer-focus:outline-none rounded-full peer dark:bg-zinc-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                    </div>
                </label>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg shadow-blue-500/20 transition-all transform active:scale-95">
                <span wire:loading.remove>Enregistrer les modifications</span>
                <span wire:loading>Chargement...</span>
            </button>

            {{-- Indicateur de sauvegarde discret --}}
            <span wire:loading class="text-zinc-500 dark:text-zinc-400 text-sm italic">
                Synchronisation...
            </span>
        </div>
    </form>
</div>