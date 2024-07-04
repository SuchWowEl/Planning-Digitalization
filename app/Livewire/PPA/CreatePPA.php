<?php

namespace App\Livewire\PPA;

use Livewire\Attributes\Title;
use Livewire\Component;

class CreatePPA extends Component
{
    public $sector_map = [
        "Social Development" => ["Education", "Health and Nutrition", "Housing and Basic Utilities"],
        "Environmental Management" => ["Forest, Coastal and Watershed Ecosystem", "Urban and Water Ecosystem"]
    ];

    #[Title('PPA Form')]
    public function render()
    {
        return view('livewire.p-p-a.create-p-p-a');
    }
}
