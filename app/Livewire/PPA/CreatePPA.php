<?php

namespace App\Livewire\PPA;

use Livewire\Attributes\Title;
use Livewire\Component;

class CreatePPA extends Component
{
    public $subsector_map = [
        1 =>
        [   "6" => "Education",
            "7" => "Health and Nutrition",
            "8" => "Housing and Basic Utilities",
            "9" => "Peace and Order and Disaster Risk Reduction and Management",
            "10" => "Social Welfare and Development",
        ],
        2 =>
        [   "11" => "Forest, Coastal and Watershed Ecosystem",
            "12" => "Urban and Water Ecosystem",
        ],
        3 =>
        [   "13" => "Agriculture/Veterinary Services",
            "14" => "Entrepreneurship, Businiess and Industry Promotion",
        ],
        4 =>
        [   "15" => "Development Administration",
            "16" => "Legislation",
            "17" => "Organization and Management",
            "18" => "Public Participation/LGU-NGO-PO LInkages",
            "19" => "Transparency and Accountability",
            "20" => "Fiscal Management",
        ],
        5 =>
        [   "21" => "Economic Support",
            "22" => "Public Administrative Support",
            "23" => "Social Support",
        ],
    ];
    public $sector_map = [
        1 => "Social Development",
        2 => "Environmental Management",
        3 => "Economic Development",
        4 => "Institutional Development",
        5 => "Infrastructure Development",
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
        $reference = new Reference;
        $oe_acode_json = $reference->oeAcodeGetter();
        $cc_typo_json = $reference->ccTypesGetter();

        return view('livewire.p-p-a.create-p-p-a', [
            'kobe' => [1 =>'bryant', 2=> '24'],
            'lebronze' => ['james'],
            // 'comp' => (object)[1 => ['name' => '', 'component' => [(object)[]]]],
            'comp' => (object)[1 => ['name' => '', 'component' => (object)[0=>(object)[]]]],
            'ind' => (object)[10 => 'b', 12 => 'a'],
            'oe_acode_json' => $oe_acode_json,
            'cc_typo_json' => $cc_typo_json
            // 'comp' => {0: {'name': '', 'component': [{}]}}
        ]);
    }

    }
}
