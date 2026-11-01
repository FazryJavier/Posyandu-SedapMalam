<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\Relationship;
use App\Enums\ResidentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resident extends Model
{
    use HasFactory, SoftDeletes;

    /** Children up to this age (in months) are followed at the posyandu. */
    public const TODDLER_MAX_MONTHS = 60;

    protected $fillable = [
        'household_id', 'nik', 'name', 'gender', 'birth_date', 'birth_place',
        'relationship', 'father_id', 'mother_id', 'occupation', 'phone',
        'health_insurance_number', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'gender' => Gender::class,
            'relationship' => Relationship::class,
            'status' => ResidentStatus::class,
            'birth_date' => 'date',
        ];
    }

    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    public function father(): BelongsTo
    {
        return $this->belongsTo(self::class, 'father_id');
    }

    public function mother(): BelongsTo
    {
        return $this->belongsTo(self::class, 'mother_id');
    }

    public function growthRecords(): HasMany
    {
        return $this->hasMany(GrowthRecord::class)->orderBy('measured_on');
    }

    /**
     * Residents young enough to be weighed at the posyandu.
     */
    public function scopeToddlers(Builder $query): Builder
    {
        return $query->whereNotNull('birth_date')
            ->where('birth_date', '>=', now()->subMonths(self::TODDLER_MAX_MONTHS)->toDateString())
            ->where('status', ResidentStatus::Active->value);
    }

    public function ageInMonths(?\DateTimeInterface $at = null): ?int
    {
        return $this->birth_date
            ? (int) $this->birth_date->diffInMonths($at ?? now(), true)
            : null;
    }
}
