<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use App\Models\Ppa;
use App\Models\Aip;
use Livewire\Form;

class PpaSection1 extends Form
{
    // TODO: enable required/validation for all fields later
    public ?Ppa $ppa;

    #[Validate('required')]
    public $status = '';

    // #[Validate('required|min:5')]
    public $aip_key = '';

    // #[Validate('required|min:5')]
    public $sector = '';

    // #[Validate('required|min:5')]
    public $subsector = '';

    // #[Validate('required|min:5')]
    public $impl_office = '';

    // #[Validate('required|min:5')]
    public $aip_ref_code = '';

    // #[Validate('required|min:5')]
    public $mfo = '';

    // #[Validate('required|min:5')]
    public $proponent = '';

    public function setSection1($id)
    {
        $ppa = null;
        if ($id == null) {
            $ppa = new Ppa;
        }
        else {
            $ppa = Ppa::find($id);
            $aip = Aip::find($ppa->aip_key);
            // $this->ppa->aip_key = $aip->year;
        }
        $this->ppa = $ppa;
        $this->status = $this->ppa->status;
        // $this->aip_key = $this->ppa->aip_key;
        $this->aip_key = Aip::find($ppa->aip_key)->year ?? 2024;
        $this->sector = $this->ppa->sector ?? 1;
        $this->subsector = $this->ppa->subsector ?? 6;
        $this->impl_office = $this->ppa->impl_office;
        $this->aip_ref_code = $this->ppa->aip_ref_code;
        $this->mfo = $this->ppa->mfo;
        $this->proponent = $this->ppa->proponent;
    }

    public function store()
    {
        // $this->validate();
        Ppa::create($this->all());
        // return $ppa_instance->id;
    }

    public function update()
    {
        // $this->validate();

        $to_input = collect($this)->except('ppa')->all();
        // NOTE: convert $to_input's aip_key year field into id
        $to_input['aip_key'] = Aip::where('year', $to_input['aip_key'])->first()->id;
        // TODO: check first if aip_key did not change except if it did not exist, otherwise dont update
        // reason: afaik year shouldnt be changed once a 'year' has been set.
        $last_saved = Ppa::updateOrCreate(
            ['id' => $this->ppa->id],
            $to_input
        );
        $to_show = $this->all();
        $to_show['ppa_new_id'] = $last_saved->id;
        session()->flash('status', $to_show);
        return $last_saved->id;
    }
}
