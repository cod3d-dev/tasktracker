<?php

namespace App\Filament\Pages;

use App\Models\task;
use App\Models\type;
use Filament\Pages\Page;
use Carbon\Carbon;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class WeeklyTasks extends Page
{
    public $timer = 0;
    public $seconds = 0;

    public $time = 0;

    protected $listeners = ['timeUpdated'];

    public $running = false;

    public $tasks;

    public $weekDates;

    public $tasksByTypeAndDate;
    public $startOfWeek;
    public $endOfWeek;
    public $averageTimePerWordByType;

    public function mount()
    {
        $this->startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $this->endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $weeklyTasks = Task::with('type')
            ->where('due_date', '>=', $this->startOfWeek)
            ->where('due_date', '<=', $this->endOfWeek)
            ->get();

        $typeIds = $weeklyTasks->pluck('type_id')->unique();
        $types = Type::whereIn('id', $typeIds)->get();

        $this->tasksByTypeAndDate = $types->mapWithKeys(function ($type) {
            $daysData = collect();
            foreach (range(0, 6) as $day) {
                $date = $this->startOfWeek->copy()->addDays($day)->format('Y-m-d');
                $daysData->put($date, 0); // Initialize word count to 0
            }

            return [$type->id => [
                'type_name' => $type->name,
                'days' => $daysData
            ]];
        });

        $taskCollection = $weeklyTasks->groupBy([
            function ($task) {
                return $task->due_date->format('Y-m-d');
            },
            function ($task) {
                return $task->type_id;
            }
        ]);

        foreach ($taskCollection as $date => $typesGroup) {
            foreach ($typesGroup as $typeId => $tasks) {
                if (isset($this->tasksByTypeAndDate[$typeId])) {
                    // Correctly update the collection by using the `put` method.
                    $currentCount = $this->tasksByTypeAndDate[$typeId]['days'][$date];
                    $newCount = $tasks->sum('words');
                    $this->tasksByTypeAndDate[$typeId]['days']->put($date, $currentCount + $newCount);
                }
            }
        }

        $this->averageTimePerWordByType = collect();

        $types->each(function ($type) {
            $lastCompletedTasks = Task::where('type_id', $type->id)
                ->whereNotNull('time_spent')
                ->whereNotNull('words')
                ->where('completed', true)
                ->latest('due_date')
                ->take(20)
                ->get();

//            dd($lastCompletedTasks);

            // Calculate the total time spent (in hours) and total word count.
            $totalTimeSpentInHours = $lastCompletedTasks->sum('time_spent');
            $totalWords = $lastCompletedTasks->sum('words');

            // Calculate the average time per word (in hours).
            $averageTimePerWordInHours = $totalWords > 0 ? $totalTimeSpentInHours / $totalWords : 0;

            // Store the result in the collection.
            $this->averageTimePerWordByType->put($type->id, $averageTimePerWordInHours);
        });

    }

//    public function getWeekDates()
//    {
//        $now = Carbon::now();
//        // Assuming the week starts on Monday and ends on Sunday
//        $startOfWeek = $now->startOfWeek(Carbon::MONDAY);
//        $weekDates = [];
//
//        for ($i = 0; $i < 7; $i++) {
//            $weekDates[] = $startOfWeek->copy()->addDays($i)->toDateString();
//        }
//
//        return $weekDates;
//    }


    public function timeUpdated($time)
    {
        $this->time = $time;
        dd($this->time);
    }

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.weekly-tasks';

    public function setTimer($time) {
        $this->timer = $time;
    }

    public function toggleTimer()
    {
        $this->running = !$this->running;

        if ($this->running) {
            $this->dispatch('start-timer');
        } else {
            $this->dispatch('stop-timer');
        }
    }








}
