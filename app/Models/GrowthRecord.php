<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrowthRecord extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class, 'child_nik', 'nik');
    }
}
