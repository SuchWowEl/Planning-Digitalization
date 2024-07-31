<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Beneficiaries extends Model
{
    use HasFactory;

    protected $table = 'beneficiaries';
    public  $timestamps = false;
    // protected $primaryKey = ['id', 'details_id'];

    public function details(): BelongsTo
    {
        return $this->belongsTo(Details::class);
    }
}
