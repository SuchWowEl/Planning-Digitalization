<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ppa extends Model
{
    use HasFactory;

    protected $table = 'ppa';
    public  $timestamps = false;

    // NOTE: Possible
    protected $fillable = ['status','aip_key','sector','subsector', 'impl_office','aip_ref_code','mfo','proponent'];
}
