<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Task;
use Carbon\Carbon;

new #[Layout('layouts::app', ['title' => 'Calendrier des tâches'])] class extends Component {
    public $date;

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

    public function render()
    {
        $startOfMonth = Carbon::parse($this->date)->startOfMonth();
        $endOfMonth = Carbon::parse($this->date)->endOfMonth();

        // On récupère les tâches du mois en cours
        $tasks = Task::whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();

        return view('calendar', [
            'days' => $this->generateCalendarDays($startOfMonth, $endOfMonth),
            'monthName' => $startOfMonth->translatedFormat('F Y'),
            'tasks' => $tasks
        ]);
    }

    private function generateCalendarDays($start, $end)
    {
        $days = [];
        // 1 = Lundi, 7 = Dimanche. Simple et efficace.
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
};
?>

<div class="max-w-6xl mx-auto pt-6 px-4">
    {{-- Header du Calendrier --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-zinc-800 dark:text-white capitalize">{{ $monthName }}</h2>
        <div
            class="flex bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden shadow-sm">
            <button wire:click="previousMonth"
                class="p-2 hover:bg-zinc-50 dark:hover:bg-zinc-800 border-r border-zinc-200 dark:border-zinc-700 transition-colors">
                <svg class="w-5 h-5 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
            <button wire:click="nextMonth" class="p-2 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                <svg class="w-5 h-5 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Grille du Calendrier --}}
    <div
        class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl shadow-sm overflow-hidden">
        {{-- Jours de la semaine --}}
        <div class="grid grid-cols-7 border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50">
            @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $dayName)
                <div class="py-3 text-center text-xs font-bold text-zinc-400 uppercase tracking-widest">{{ $dayName }}</div>
            @endforeach
        </div>

        {{-- Jours du mois --}}
        <div class="grid grid-cols-7">
            @foreach($days as $day)
                <div
                    class="min-h-30 border-b border-r border-zinc-100 dark:border-zinc-800 p-2 {{ $day['isCurrentMonth'] ? '' : 'bg-zinc-50/50 dark:bg-zinc-950/20' }}">
                    <div class="flex justify-between items-center mb-2">
                        <span
                            class="text-sm font-medium {{ $day['isToday'] ? 'bg-blue-600 text-white w-7 h-7 flex items-center justify-center rounded-full shadow-lg shadow-blue-500/30' : ($day['isCurrentMonth'] ? 'text-zinc-700 dark:text-zinc-300' : 'text-zinc-300 dark:text-zinc-600') }}">
                            {{ $day['date']->day }}
                        </span>
                    </div>

                    {{-- Affichage des tâches pour ce jour --}}
                    <div class="space-y-1">
                        @foreach($tasks->whereBetween('created_at', [$day['date']->copy()->startOfDay(), $day['date']->copy()->endOfDay()]) as $task)
                                    <a href="{{ route('tasks.show', $task->id) }}" wire:navigate
                                        class="block px-2 py-1 text-[10px] leading-tight rounded-md border 
                                                                                                                                                                               {{ $task->state
                            ? 'bg-green-50 border-green-100 text-green-700 dark:bg-green-900/20 dark:border-green-800 dark:text-green-400'
                            : 'bg-blue-50 border-blue-100 text-blue-700 dark:bg-blue-900/20 dark:border-blue-800 dark:text-blue-400' }} 
                                                                                                                                                                               truncate transition-transform hover:scale-105">
                                        {{ $task->title }}
                                    </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>