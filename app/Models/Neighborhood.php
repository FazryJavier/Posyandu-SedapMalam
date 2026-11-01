<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Neighborhood extends Model
{
    use HasFactory;

    protected $fillable = ['rw', 'rt', 'head_name', 'phone'];

    public function households(): HasMany
    {
        return $this->hasMany(Household::class);
    }

    public function getLabelAttribute(): string
    {
        return "RT {$this->rt} / RW {$this->rw}";
    }
}
