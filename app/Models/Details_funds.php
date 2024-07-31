<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Details_funds extends Model
{
    use HasFactory;

    protected $table = 'details_funds';
    public  $timestamps = false;
    protected $primaryKey = 'details_id';

    // NOTE: Possible
    protected $fillable = ['details_id','fund_id'];
}
