<?php

namespace App\Livewire\Forms;

use App\Models\Beneficiaries;
use App\Models\Details_funds;
// use App\Models\Indicators;
use GuzzleHttp\Utils;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use App\Models\Ppa;
use App\Models\Details;
// use App\Models\Dates;
use Livewire\Form;
use Nette\Utils\DateTime;
use function GuzzleHttp\json_encode;

class PpaSection2 extends Form
{
    public ?Details $details;
    public ?Beneficiaries $beneficiaries;
    public $details_funds; // collection
    public $dates; // collection

    // #[Validate('required')]
    public $ppa = '';

    // #[Validate('required')]
    public $title = '';

    // #[Validate('required')]
    public $location = '';

    // #[Validate('required')]
    public $justification = '';

    // #[Validate('required')]
    public $objective = '';

    // #[Validate('required')]
    public $desc = '';

    // #[Validate('required')]
    public $type = '';

    // #[Validate('required')]
    public $male = '';

    // #[Validate('required')]
    public $female = '';

    // #[Validate('required|date|before:today', message: 'Please set the date before today')]
    public $start = '';

    // #[Validate('required|date|after:start', message: 'Please set the date before today')]
    public $end = '';

    // #[Validate('required')]
    public $indicators = [''];

    // #[Validate('required')]
    public $fund = [];

    // #[Validate('required_if:array_intersect(fund,["General Fund Proper","Trust Fund"]),true', message: 'subfund required')]
    public $subfund = [];

    // #[Validate('required_if:array_intersect(fund,["General Fund Proper","Trust Fund"]),true', message: 'subfund required')]
    public $subfund_old = [];


    // public function rules(){
    //     return [
    //         'title' => 'required',
    //         'location' => 'required',
    //         'justification' => 'required',
    //         'objective' => 'required',
    //         'desc' => 'required',
    //         'type' => 'required',
    //         'male' => 'required',
    //         'female' => 'required',
    //         'start' => 'nullable|date|before:today', // required_if end exists
    //         'end' => 'nullable|date|after:start', // required_if start exists
    //         // 'fund' => 'required',
    //         'subfund' => 'required',
    //     ];
    // }

    // public function messages(){
    //     return [
    //         'start' => 'Please set the date before today',
    //         'end' => 'Please set the date before today',
    //         'subfund' => 'subfund required',
    //     ];
    // }

    public $fund_gfp = [
        'Regular' => 31,
        '20% Development Fund' => 32,
        'DRRM Fund' => 33,
        'Economic Enterprise' => 34,
        'Hospital' => 35,
        'Market' => 36,
        'Slaughterhouse' => 37,
        'Terminal' => 38
    ];
    public $fund_tf = [
        'Financial Assistance' => 39,
        'Aid from National Government' => 40,
        'Financial Assistance from other LGUs' => 41,
        'Grants from outside sources' => 42,
        'LGU Fund Transfer' => 43,
        'Other Receipts' => 44
    ];
    public $fund_sef = [ 'Special Education Fund' => 29 ];


    public function setSection2(int $ppa_id = null)
    {
        $this->details = Details::where('ppa', $ppa_id)->first() ?? new Details;
        $this->beneficiaries = Beneficiaries::where('details_id', $this->details->id)->first() ?? new Beneficiaries;

        $this->ppa = $this->details->ppa;
        $this->title = $this->details->title ?? '';
        $this->location = $this->details->location ?? '';
        $this->justification = $this->details->justification ?? '';
        $this->objective = $this->details->objective ?? '';
        $this->desc = $this->details->desc ?? '';
        $this->type = $this->beneficiaries->type ?? '';
        $this->male = $this->beneficiaries->male ?? null;
        $this->female = $this->beneficiaries->female ?? null;
        // TODO: find a way to assign values to date accordingly
        $this->start = $this->details->start ?? null;
        $this->end = $this->details->end ?? null;
        if ($this->details->indicators == null) {
            $this->details->indicators = '[""]';
        }
        $this->indicators = collect(json_decode($this->details->indicators, true));
        if ($this->details->id != null) {
            $this->subfund_old = $this->subfund = Details::find($this->details->id)->references()->get()->pluck('value');
        } else {
            $this->subfund_old = $this->subfund = collect([]);
        }
        // TODO: find a way for fund to reflect accordingly
        $this->fund = $this->details->fund;
        // $this->subfund = $this->details->subfund;

    }

     public function store()
     {
         // $this->validate();
         // Details::create($this->all());
         // return $ppa_instance->id;
     }

    public function update(int $ppa_id)
    {
        // $this->validate();
        // TODO: exempt indicators
        $this->details->ppa = $ppa_id;
        $this->details->title = $this->title;
        $this->details->location = $this->location;
        $this->details->justification = $this->justification;
        $this->details->objective = $this->objective;
        $this->details->desc = $this->desc;
        $this->details->indicators = Utils::jsonEncode($this->indicators);
        $this->details->start = $this->start;
        $this->details->end = $this->end;
        $this->details->save();

        // TODO: Convert for (1) Source of Fund and (2) Implementation Period
        $sumn = $this->details->id;
        $this->beneMapper($sumn);
        // $this->dateMapper($this->details->id);
        $this->sofMapper($sumn);

        // $to_input['aip_key'] = Aip::where('year', $to_input['aip_key'])->first()->id;
        // $to_show['ppa_new_id'] = $last_saved->id;
        $this->details->fresh();
        // session()->flash('status2', $to_show);
        // session()->flash('status3', $this->details->id);
        // return $last_saved->id;
    }

    protected function beneMapper(int $details_id) {
        $this->beneficiaries->details_id = $this->details->id;
        $this->beneficiaries->male = $this->male;
        $this->beneficiaries->female = $this->female;
        $this->beneficiaries->type = $this->type;
        // dump($this->beneficiaries);
        $this->beneficiaries->save();
    }

    protected function sofMapper(int $details_id) {
        // TODO: check if subfund_old is consistent with the database (use fresh or refresh)
        $to_add = []; // contains id of details_fund's details_id
        $to_delete = [];
        $subfund = $this->subfund;
        $subfund_old = $this->subfund_old;
        foreach ([$this->fund_gfp, $this->fund_tf, $this->fund_sef, [ 'GF - Proper' => 30 ]] as $arr) {
            foreach ($subfund as $ssof) {
                if (array_key_exists($ssof, $arr)) {
                    array_push($to_add, $arr[$ssof]);
                    $subfund->pull($ssof);
                }
            }
            foreach ($subfund_old as $ssof) {
                if (array_key_exists($ssof, $arr)) {
                    array_push($to_delete, $arr[$ssof]);
                    $subfund_old->pull($ssof);
                    break;
                }
            }
        }
        // $to_delete = collect($subfund_old)->diff(collect($subfund));
        $to_delete = array_diff($to_delete, $to_add);
        foreach ($to_add as $ssof_id) {
            $df = Details_funds::where('details_id', $details_id)
                ->where('fund_id', $ssof_id)
                ->first();
            if ($df == null) {
                Details_funds::updateOrCreate(
                    ['details_id' => $details_id, 'fund_id' => $ssof_id]
                );
            }
        }
        foreach ($to_delete as $ssof_id) {
            Details_funds::where('details_id', $details_id)
                ->where('fund_id', $ssof_id)
                ->delete();
        }
        // TODO: delete unneeded existing details_funds
    }
}
