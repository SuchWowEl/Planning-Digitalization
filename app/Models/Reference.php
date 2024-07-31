<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    use HasFactory;

    protected $table = 'reference';
    public $timestamps = false;

    // public function oe_acode_setter() {
    //     $this->$oe_acode_json = Reference::where('id','<=',172)->where('id','>=',45)->get();
    // }
    //
    // public function oe_acode_getter() {
    //     // return Reference::where('id','<=',172)->where('id','>=',45)->get();
    //     return $oe_acode_json;
    // }

    public $oe_codes;
    public $cc_typos;
    protected $fillable = [];

    public function details(): BelongsToMany
    {
        return $this->belongsToMany(Details::class, 'details_funds', 'fund_id', 'details_id');
    }

    public function oeAcodeGetter()
    {
        if ($this->oe_codes == null) {
            $this->oe_codes = Reference::where('id','<=',172)->where('id','>=',45)->get();
        }
        return $this->oe_codes;
    }

    public function ccTypesGetter()
    {
        if ($this->cc_typos == null) {
            $this->cc_typos = Reference::where('id','<=',452)->where('id','>=',173)->get();
        }
        return $this->cc_typos;
    }
}
