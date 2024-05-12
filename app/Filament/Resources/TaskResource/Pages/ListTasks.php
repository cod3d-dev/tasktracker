<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Filament\Resources\TaskResource\Widgets\CalendarWidget;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    use ExposesTableToWidgets;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

//    public function getHeader(): ?View
//    {
//        return view('livewire.weekly-summary');
//    }

    public function getHeading(): string
    {
        return __('Custom Page Heading');
    }



    protected function getHeaderWidgets(): array
    {
        return [
            TaskResource\Widgets\TasksOverview::class,
//            CalendarWidget::class,
//            TaskResource\Widgets\TasksOverview::class,
//            TaskResource\Widgets\TasksOverview::class,
//            TaskResource\Widgets\TasksOverview::class,
//            CalendarWidget::class
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }

//    public function getColumns(): int | string | array
//    {
//        return 3;
//    }




}
