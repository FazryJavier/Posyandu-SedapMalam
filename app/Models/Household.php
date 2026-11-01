<?php

namespace App\Models;

use App\Enums\Relationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Household extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'neighborhood_id', 'family_card_number', 'address', 'phone',
        'has_clean_water', 'has_sanitary_toilet', 'receives_social_assistance',
        'health_insurance', 'notes',
    ];

    /** Never serialize the secret link token by accident (JSON, logs, Livewire state). */
    protected $hidden = ['access_token'];

    protected function casts(): array
    {
        return [
            'access_token_generated_at' => 'datetime',
            'last_viewed_at' => 'datetime',
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

    /**
     * Create (or replace) the secret link token. Any previously shared link stops working.
     */
    public function generateAccessToken(): string
    {
        $token = Str::random(40);

        $this->forceFill([
            'access_token' => $token,
            'access_token_generated_at' => now(),
        ])->save();

        return $token;
    }

    public function revokeAccessToken(): void
    {
        $this->forceFill(['access_token' => null, 'access_token_generated_at' => null])->save();
    }

    /**
     * The private URL a family opens to follow its children's growth.
     */
    public function accessUrl(): ?string
    {
        return $this->access_token ? route('family.show', ['token' => $this->access_token]) : null;
    }

    public static function findByAccessToken(string $token): ?self
    {
        return static::query()->where('access_token', $token)->first();
    }

    public function head(): HasOne
    {
        return $this->hasOne(Resident::class)->where('relationship', Relationship::Head->value);
    }
}
