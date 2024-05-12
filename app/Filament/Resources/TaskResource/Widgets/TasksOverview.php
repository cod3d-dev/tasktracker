<?php

namespace App\Filament\Resources\TaskResource\Widgets;

use App\Filament\Resources\TaskResource\Pages\ListTasks;
use App\Models\task;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TasksOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;


    protected function getTablePage(): string
    {
        return ListTasks::class;
    }

    protected function getStats(): array
    {

        $tasks = Task::where('due_date', '>=', now())->selectRaw('sum(words) as words')->select('type_id')->groupBy('type_id')->get();
////        dd($tasks);
////        $taskTypes = Task::select('type_id')->where('due_date', '>=', now())->distinct()->get();
//
//        foreach ($tasks as $task) {
//            dd($task->type_id);
//
////            $tasks = Task::where('type_id', $taskType->type_id)->where('due_date', '>=', now())->sum('words');
//        }
//        dd($taskTypes);


        return [
//            foreach ($this->tasks as $task) {
//                Stat::make('Translations', $this->getPageTableQuery()->where('type_id', 1)->sum('words') . "/ " ),
////            $tasks = Task::where('type_id', $taskType->type_id)->where('due_date', '>=', now())->sum('words');
//            }

            Stat::make('Translations', $this->getPageTableQuery()->where('type_id', 1)->sum('words') . "/ " ),
            Stat::make('Proofreading', $this->getPageTableQuery()->where('type_id', 2)->sum('words')),
            Stat::make('M-Proof', $this->getPageTableQuery()->where('type_id', 4)->sum('words')),
            Stat::make('LSO', $this->getPageTableQuery()->where('type_id', 3)->count()),
        ];
    }

    protected int | string | array $columnSpan = '1';
    public function getColumns(): int
    {
        return 4;
    }

//    protected function getHeaderWidgetsColumns(): int | array
//    {
//        return [
//            'sm' => 2,
//            'md' => 3,
//            'xl' => 4,
//        ];
//    }


}
