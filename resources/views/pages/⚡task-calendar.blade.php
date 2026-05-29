<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Task;
use Carbon\Carbon;

new #[Layout('layouts::app', ['title' => 'Calendrier des tâches'])] class extends Component {
    public $date;
    public $showModal = false;
    public $selectedDateString = '';
    public $selectedDateTasks = [];
    public $rawSelectedDate = '';

    public function mount()
    {
        $this->date = Carbon::now();
    }

    public function previousMonth()
    {
        $this->date = Carbon::parse($this->date)->subMonth();
    }

    public function nextMonth()
    {
        $this->date = Carbon::parse($this->date)->addMonth();
    }

    #[\Livewire\Attributes\Computed]
    public function calendarData()
    {
        $startOfMonth = Carbon::parse($this->date)->startOfMonth();
        $endOfMonth = Carbon::parse($this->date)->endOfMonth();

        // Charger toutes les tâches pour la plage complète de la grille du calendrier (évite le problème N+1)
        $startOfGrid = $startOfMonth->copy()->startOfWeek(1)->startOfDay();
        $endOfGrid = $endOfMonth->copy()->endOfWeek(7)->endOfDay();

        return [
            'days' => $this->generateCalendarDays($startOfMonth, $endOfMonth),
            'monthName' => $startOfMonth->translatedFormat('F Y'),
            'tasks' => Task::whereBetween('created_at', [$startOfGrid, $endOfGrid])->get()
        ];
    }

    private function generateCalendarDays($start, $end)
    {
        $days = [];
        // 1 = Lundi, 7 = Dimanche
        $date = $start->copy()->startOfWeek(1);
        $endOfGrid = $end->copy()->endOfWeek(7);

        while ($date <= $endOfGrid) {
            $days[] = [
                'date' => $date->copy(),
                'isCurrentMonth' => $date->month === $start->month,
                'isToday' => $date->isToday(),
            ];
            $date->addDay();
        }
        return $days;
    }

    public function selectDate($dateString)
    {
        $this->rawSelectedDate = $dateString;
        $date = Carbon::parse($dateString);
        $this->selectedDateString = $date->translatedFormat('d F Y');
        
        $this->loadSelectedDateTasks();
        $this->showModal = true;
    }

    private function loadSelectedDateTasks()
    {
        if ($this->rawSelectedDate) {
            $start = Carbon::parse($this->rawSelectedDate)->startOfDay();
            $end = Carbon::parse($this->rawSelectedDate)->endOfDay();
            $this->selectedDateTasks = Task::whereBetween('created_at', [$start, $end])->get()->toArray();
        }
    }

    public function toggleTaskState($taskId)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->state = !$task->state;
            $task->save();
            
            // Recharger les tâches de ce jour pour mettre à jour le modal réactivement
            $this->loadSelectedDateTasks();
            
            // Dispatcher un toast dynamique pour notifier du succès
            $message = $task->state ? 'Tâche marquée comme complétée.' : 'Tâche repassée en cours.';
            $this->dispatch('toast', heading: 'Statut mis à jour', text: $message, variant: 'success');
        }
    }
};
?>

<div class="max-w-6xl mx-auto pt-6 px-4 pb-12">
    {{-- Header du Calendrier --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-extrabold tracking-tight bg-linear-to-r from-zinc-900 to-zinc-600 dark:from-white dark:to-zinc-400 bg-clip-text text-transparent capitalize">
                {{ $this->calendarData['monthName'] }}
            </h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Visualisez votre planning et complétez vos tâches d'un simple clic.</p>
        </div>
        <div class="flex bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-sm p-1 gap-1">
            <button wire:click="previousMonth"
                class="p-2 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-800 text-zinc-600 dark:text-zinc-400 transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
            <button wire:click="nextMonth" 
                class="p-2 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-800 text-zinc-600 dark:text-zinc-400 transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Grille du Calendrier --}}
    <div class="bg-gray-200 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl shadow-xl overflow-hidden">
        {{-- Jours de la semaine --}}
        <div class="grid grid-cols-7 border-b border-zinc-250 dark:border-zinc-800 bg-gray-4 dark:bg-zinc-800/20">
            @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $dayName)
                <div class="py-4 text-center text-xs font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">{{ $dayName }}</div>
            @endforeach
        </div>

        {{-- Jours du mois --}}
        <div class="grid grid-cols-7">
            @foreach($this->calendarData['days'] as $day)
                @php
                    $dayStart = $day['date']->copy()->startOfDay();
                    $dayEnd = $day['date']->copy()->endOfDay();
                    $dayTasks = $this->calendarData['tasks']->filter(function($t) use ($dayStart, $dayEnd) {
                        return $t->created_at >= $dayStart && $t->created_at <= $dayEnd;
                    });
                    $taskCount = $dayTasks->count();
                    $completedCount = $dayTasks->where('state', true)->count();
                    $allCompleted = $taskCount > 0 && $taskCount === $completedCount;
                @endphp
                <div wire:click="selectDate('{{ $day['date']->toDateString() }}')"
                    class="h-28 border-b border-r border-zinc-100 dark:border-zinc-800 p-3 transition-all duration-300 hover:bg-zinc-50/80 dark:hover:bg-zinc-800/30 cursor-pointer flex flex-col justify-between relative group {{ $day['isCurrentMonth'] ? '' : 'bg-zinc-50/20 dark:bg-zinc-950/5' }}">
                    
                    {{-- Numéro du jour --}}
                    <div class="flex justify-between items-center">
                        <span
                            class="text-sm font-semibold {{ $day['isToday'] ? 'bg-linear-to-r from-blue-600 to-indigo-600 text-white w-7 h-7 flex items-center justify-center rounded-full shadow-md shadow-blue-500/20' : ($day['isCurrentMonth'] ? 'text-zinc-700 dark:text-zinc-300' : 'text-zinc-300 dark:text-zinc-600') }}">
                            {{ $day['date']->day }}
                        </span>
                    </div>

                    {{-- Résumé des tâches (affiche le nombre ou la complétion) --}}
                    @if($taskCount > 0)
                        <div class="flex items-center justify-center mt-auto">
                            @if($allCompleted)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-500/10 text-green-600 dark:bg-green-500/25 dark:text-green-400 border border-green-500/20 transition-all duration-300 group-hover:scale-105">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>{{ $taskCount }} {{ $taskCount > 1 ? 'tâches' : 'tâche' }}</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-500/10 text-indigo-600 dark:bg-indigo-500/25 dark:text-indigo-400 border border-indigo-500/20 transition-all duration-300 group-hover:scale-105">
                                    <span class="relative flex h-1.5 w-1.5 mr-0.5">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-indigo-500"></span>
                                    </span>
                                    <span>{{ $completedCount }}/{{ $taskCount }} {{ $taskCount > 1 ? 'tâches' : 'tâche' }}</span>
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Modal Détails des Tâches --}}
    <flux:modal wire:model.self="showModal" class="min-w-md max-w-lg">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg" class="text-zinc-900 dark:text-white">Tâches du {{ $selectedDateString }}</flux:heading>
                <flux:subheading class="mt-1">Gérez vos tâches et marquez-les comme terminées d'un simple clic.</flux:subheading>
            </div>
                <hr class="border-zinc-200 dark:border-zinc-700">
            <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                @forelse($selectedDateTasks as $task)
                    <div class="p-4 rounded-2xl border border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/10 hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition duration-200 flex items-center justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-zinc-900 dark:text-white truncate {{ $task['state'] ? 'line-through text-zinc-400 dark:text-zinc-500 font-medium' : '' }}">
                                {{ $task['title'] }}
                            </h4>
                            @if(!empty($task['detail']))
                                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 line-clamp-2 leading-relaxed">
                                    {{ $task['detail'] }}
                                </p>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-3">
                            {{-- Case à cocher interactive personnalisée --}}
                            <button wire:click="toggleTaskState({{ $task['id'] }})" 
                                class="flex items-center justify-center w-6 h-6 rounded-full border-2 transition duration-200 focus:outline-none {{ $task['state'] ? 'bg-green-500 border-green-500 text-white' : 'border-zinc-300 dark:border-zinc-700 hover:border-indigo-500 dark:hover:border-indigo-400 bg-white dark:bg-zinc-850' }}">
                                @if($task['state'])
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </button>
                            
                            {{-- Bouton de navigation vers la page détails --}}
                            <flux:button href="{{ route('tasks.show', $task['id']) }}" wire:navigate size="sm" icon="eye" variant="subtle" />
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                        <div class="w-12 h-12 rounded-full bg-zinc-105 dark:bg-zinc-800 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-zinc-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Aucune tâche</h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Vous n'avez planifié aucune tâche pour cette journée.</p>
                        <button wire:navigate href="{{ route('tasks.create') }}" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            Créer une tâche
                        </button>
                    </div>
                @endforelse
            </div>

            <div class="flex justify-end pt-2">
                <flux:modal.close>
                    <flux:button variant="ghost" class="rounded-xl">Fermer</flux:button>
                </flux:modal.close>
            </div>
        </div>
    </flux:modal>
</div>