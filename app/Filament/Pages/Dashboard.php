<?php

namespace App\Filament\Pages;

class Dashboard extends \Filament\Pages\Dashboard
{
    // ...
    protected static ?string $title = 'Finance dashboard';

    public function getColumns(): int | string | array
    {
        return 4;
    }
}
