<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrowthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'posyandu_session_id', 'resident_id', 'measured_on', 'age_in_months',
        'weight_kg', 'height_cm', 'head_circumference_cm', 'arm_circumference_cm',
        'bmi', 'nutritional_status', 'notes', 'measured_by',
    ];

    protected function casts(): array
    {
        return [
            'measured_on' => 'date',
            'weight_kg' => 'decimal:2',
            'height_cm' => 'decimal:1',
            'head_circumference_cm' => 'decimal:1',
            'arm_circumference_cm' => 'decimal:1',
            'bmi' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        // Derive age and BMI so charts and reports never depend on manual input.
        static::saving(function (self $record): void {
            $record->measured_on ??= $record->session?->held_on ?? now();

            $birthDate = $record->resident?->birth_date;
            if ($birthDate) {
                $record->age_in_months = (int) $birthDate->diffInMonths($record->measured_on, true);
            }

            $record->bmi = self::calculateBmi($record->weight_kg, $record->height_cm);
        });
    }

    /**
     * BMI = weight (kg) / height (m) squared. Null when either value is missing.
     */
    public static function calculateBmi(float|string|null $weightKg, float|string|null $heightCm): ?float
    {
        $weight = (float) $weightKg;
        $heightM = (float) $heightCm / 100;

        if ($weight <= 0 || $heightM <= 0) {
            return null;
        }

        return round($weight / ($heightM ** 2), 2);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(PosyanduSession::class, 'posyandu_session_id');
    }

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    public function measurer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'measured_by');
    }
}
