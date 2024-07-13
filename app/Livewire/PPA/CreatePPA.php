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
    public $headers = [
        // 11
        // 'name' => 'PPA Component',
        'location' => 'Location',
        'output' => 'Output',
        'res_inputs' => 'Resource Inputs',
        'oe_acode' => 'Object of Expenditure/Account Code',
        'ps' => 'PS',
        'mooe' => 'MOOE',
        'co' => 'CO',
        'total' => 'Total', // not included in database
        'source_of_fund' => 'Source of Fund',
        'r_office' => 'Responsible Office/s',
    ];

    #[Title('PPA Form')]
    public function render()
    {
        return view('livewire.p-p-a.create-p-p-a');
    }
}
