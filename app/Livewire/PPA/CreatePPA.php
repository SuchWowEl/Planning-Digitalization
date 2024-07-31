<?php

namespace App\Livewire\PPA;

use App\Livewire\Forms\PpaSection1;
use App\Livewire\Forms\PpaSection2;
use App\Models\Ppa;
use App\Models\Reference;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\Aip;

class CreatePPA extends Component
{
    public PpaSection1 $section1;
    public PpaSection2 $section2;
    public Reference $reference;

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
            "14" => "Entrepreneurship, Business and Industry Promotion",
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

    public function mount(int $ppa_id = null)
    {
        $this->section1->setSection1($ppa_id);
        $this->section2->setSection2($ppa_id);
        $this->reference = new Reference;
    }

    public function save()
    {
        $ppa_id = $this->section1->update();
        $this->section2->update($ppa_id);
    }

    public function redir(int $id){
        $the_id = $this->section1->update();
        return view(
            'test', [
                'req' => $id,
                'isThere' => true,
            ]
        );
    }

    #[Title('PPA Form')]
    public function render()
    {
        $oe_acode_json = $this->reference->oeAcodeGetter();
        $cc_typo_json = $this->reference->ccTypesGetter();

        return view('livewire.p-p-a.create-p-p-a', [
            'kobe' => [1 =>'bryant', 2=> '24'],
            'lebronze' => ['james'],
            // 'comp' => (object)[1 => ['name' => '', 'component' => [(object)[]]]],
            'comp' => (object)[1 => ['name' => '', 'component' => (object)[0=>(object)[]]]],
            // 'ind' => (object)[10 => 'b', 12 => 'a'],
            'oe_acode_json' => $oe_acode_json,
            'cc_typo_json' => $cc_typo_json
            // 'comp' => {0: {'name': '', 'component': [{}]}}
        ]);
    }

    public function result(Request $request){
        $aip = new Aip;
        $ppa = new Ppa;
        $year_query = Aip::where('year', $request->ppa['year'])->first();

        $aip_id = $this->aip_id_fetcher($aip, $request);
        // $ppa_id = $this->ppa_creator($ppa, $aip_id, $request);
        $ppa_id = 1;
        // echo $uwu;

        return view(
            'test', [
                'req' => json_encode($request->ppa),
                'isThere' => $aip_id,]
        );
    }

    private function aip_id_fetcher(Aip $aip, Request $request){
        // print($request);
        $id = Aip::where('year', $request->ppa['year'])->first()->id;

        // TODO: make sure that smth like '202x' is rejected (intval converts it to 202)
        if (is_null($id) && is_int(intval($request->ppa['year']))) {
            $aip->year = $request->ppa['year'];
            $aip->save();
            $id = Aip::where('year', $request->ppa['year'])->first()->id;
        }

        return $id;
    }

    private function ppa_creator(Ppa $ppa, int $aip_id, Request $request) {
        // print($request);
        $ppa->aip_key = $aip_id;
        $ppa->status = $request->ppa['ppa_status'];
        $ppa->sector = $request->ppa['sector'];
        $ppa->subsector = $request->ppa['subsector'];
        $ppa->proponent = $request->ppa['proponent'];
        $ppa->mfo = $request->ppa['mfo'];
        $ppa->impl_office = $request->ppa['impl_office'];
        $ppa->aip_ref_code = $request->ppa['aip_ref_code'];
        $ppa->save();

        return $ppa->id;
    }
}
