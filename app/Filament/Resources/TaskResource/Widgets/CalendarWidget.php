<?php

namespace App\Filament\Resources\TaskResource\Widgets;

use Filament\Widgets\Widget;
use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;


class CalendarWidget extends FullCalendarWidget
{
//    protected static string $view = 'filament.resources.task-resource.widgets.calendar-widget';

public Model | string | null $model = Task::class;

public function fetchEvents(array $fetchIinfo): array
{
    return Task::where('due_date', '>=', $fetchIinfo['start'])
        ->where('due_date', '<=', $fetchIinfo['end'])
        ->get()
        ->map(function (Task $task) {
            return [
                'id' => $task->id,
                'title' => $task->description,
                'start' => $task->due_date,
                'end' => $task->due_date,
            ];
        })
        ->toArray();
}

    public static function canView(): bool
    {
        return true;

}
}
