<?php

namespace App\Models;

use App\Enums\Relationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Household extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'neighborhood_id', 'family_card_number', 'address', 'phone',
        'has_clean_water', 'has_sanitary_toilet', 'receives_social_assistance',
        'health_insurance', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'has_clean_water' => 'boolean',
            'has_sanitary_toilet' => 'boolean',
            'receives_social_assistance' => 'boolean',
        ];
    }

    public function neighborhood(): BelongsTo
    {
        return $this->belongsTo(Neighborhood::class);
    }

    public function residents(): HasMany
    {
        return $this->hasMany(Resident::class);
    }

    public function head(): HasOne
    {
        return $this->hasOne(Resident::class)->where('relationship', Relationship::Head->value);
    }
}
