<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dates extends Model
{
    use HasFactory;

    protected $table = 'date';
    public  $timestamps = false;
    protected $primaryKey = ['details_id', 'ends'];

    // NOTE: Possible
    protected $fillable = ['year','month','day'];

    public function details(): BelongsTo
    {
        return $this->belongsTo(Details::class);
    }
}
