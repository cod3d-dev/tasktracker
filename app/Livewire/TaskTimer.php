<?php

namespace App\Livewire;

use Livewire\Component;

class TaskTimer extends Component
{
    public $running;
    public function render()
    {
        return view('livewire.task-timer');
    }
}
