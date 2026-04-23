<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Layout;
use App\Models\Task;
use Flux\Flux;

new #[Layout('layouts::app', ['title' => 'Créer une tâche'])] class extends Component {
    #[Validate('required|max:100')]
    public string $title = '';

    #[Validate('required|max:500')]
    public string $detail = '';

    public string $messageOk = '';

    public function save()
    {
        $this->validate();
        $task = new Task;
        $task->title = $this->title;
        $task->detail = $this->detail;
        $task->save();
        Flux::toast(variant: "success", text: "Tâche créée avec succès.");
        return $this->redirectRoute('tasks.index', navigate: true);
    }
};
?>

<div class="max-w-2xl mx-auto p-6">

    <form wire:submit="save" class="space-y-6">
        @csrf
        <flux:field>
            <flux:label>Titre de la tâche</flux:label>
            <flux:input wire:model="title" placeholder="Ex: Finir la pose du parquet..." icon="pencil-square" />
            <flux:error name="title" />
        </flux:field>

        <flux:field>
            <flux:label>Détails</flux:label>
            <flux:textarea wire:model="detail" placeholder="Décrivez les étapes ici..." rows="5" />
            <flux:error name="detail" />
        </flux:field>

        <div class="flex items-center gap-3">
            <flux:button type="submit" variant="primary" icon="plus">
                Enregistrer la tâche
            </flux:button>

            <flux:button href="{{ route('tasks.index') }}" variant="ghost">
                Annuler
            </flux:button>
        </div>
    </form>
</div>