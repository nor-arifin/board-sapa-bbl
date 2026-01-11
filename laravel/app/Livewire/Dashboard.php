<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Screening;
use App\Models\Facility;
use App\Models\Region;

#[Layout('layouts.landing')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard');
    }
}
