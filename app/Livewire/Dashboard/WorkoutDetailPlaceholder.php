<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class WorkoutDetailPlaceholder extends Component
{
    public string $type = 'workout';

    public string $date = '';

    public function mount(string $date, string $type = 'workout'): void
    {
        $this->date = $date;
        $this->type = $type === 'running' ? 'Running' : 'Strength Workout';
    }

    public function render()
    {
        return view('livewire.dashboard.workout-placeholder')->layout('layouts.dashboard.main');
    }
}
