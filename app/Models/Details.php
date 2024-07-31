<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Details extends Model
{
    use HasFactory;

    protected $table = 'details';
    public  $timestamps = false;

    public function references(): BelongsToMany
    {
        return $this->belongsToMany(Reference::class, 'details_funds', 'details_id', 'fund_id');
    }
}
