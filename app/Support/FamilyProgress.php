<?php

namespace App\Support;

use App\Models\GrowthRecord;
use App\Models\Household;
use App\Models\PosyanduSession;
use App\Models\Resident;
use Illuminate\Support\Collection;

/**
 * Assembles what a family is allowed to see on its private progress page.
 *
 * Only toddlers of the household are exposed, and only their measurements.
 * NIK, family card number, address and other household members are never part
 * of this data.
 */
class FamilyProgress
{
    /**
     * @return array{household_head: string|null, children: Collection<int, array<string, mixed>>, next_session: PosyanduSession|null}
     */
    public static function for(Household $household): array
    {
        $children = $household->residents()
            ->toddlers()
            ->with(['growthRecords' => fn ($query) => $query->orderBy('measured_on')])
            ->orderBy('birth_date', 'desc')
            ->get()
            ->map(fn (Resident $child) => self::child($child));

        return [
            'household_head' => $household->head?->name,
            'children' => $children,
            'next_session' => PosyanduSession::query()
                ->whereDate('held_on', '>=', today())
                ->orderBy('held_on')
                ->first(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function child(Resident $child): array
    {
        /** @var Collection<int, GrowthRecord> $records */
        $records = $child->growthRecords->values();
        $labels = $records->map(fn (GrowthRecord $r) => $r->measured_on->translatedFormat('M'))->all();

        return [
            'name' => $child->name,
            'gender' => $child->gender->value,
            'age' => self::ageLabel($child->ageInMonths()),
            'records' => $records->reverse()->values(),
            'latest' => $records->last(),
            'advice' => self::advice($records),
            'weight_chart' => SvgLineChart::build($records->map(fn ($r) => (float) $r->weight_kg)->all(), $labels),
            'height_chart' => SvgLineChart::build($records->map(fn ($r) => $r->height_cm !== null ? (float) $r->height_cm : null)->all(), $labels),
            'bmi_chart' => SvgLineChart::build($records->map(fn ($r) => $r->bmi !== null ? (float) $r->bmi : null)->all(), $labels),
        ];
    }

    public static function ageLabel(?int $months): string
    {
        if ($months === null) {
            return '-';
        }

        $years = intdiv($months, 12);
        $rest = $months % 12;

        return match (true) {
            $years === 0 => "{$rest} bulan",
            $rest === 0 => "{$years} tahun",
            default => "{$years} tahun {$rest} bulan",
        };
    }

    /**
     * Simple, non-diagnostic message based on weight change between weighings
     * (the "naik / tidak naik" rule used on the KMS growth card).
     *
     * @param  Collection<int, GrowthRecord>  $records  oldest first
     * @return array{level: string, message: string}
     */
    public static function advice(Collection $records): array
    {
        if ($records->isEmpty()) {
            return ['level' => 'info', 'message' => 'Belum ada data penimbangan. Bawa anak ke posyandu agar pertumbuhannya dapat dipantau.'];
        }

        if ($records->count() === 1) {
            return ['level' => 'info', 'message' => 'Ini penimbangan pertama. Perkembangan akan terlihat setelah penimbangan berikutnya.'];
        }

        $last = $records->last();
        $previous = $records->get($records->count() - 2);
        $diff = round((float) $last->weight_kg - (float) $previous->weight_kg, 2);

        $notRisingTwice = false;
        if ($records->count() >= 3 && $diff <= 0) {
            $before = $records->get($records->count() - 3);
            $notRisingTwice = (float) $previous->weight_kg <= (float) $before->weight_kg;
        }

        if ($notRisingTwice) {
            return ['level' => 'alert', 'message' => 'Berat badan tidak naik dalam dua penimbangan berturut-turut. Mohon segera periksakan anak ke kader atau puskesmas.'];
        }

        if ($diff > 0) {
            return ['level' => 'good', 'message' => 'Berat badan naik '.self::kg($diff).' dibanding penimbangan sebelumnya. Pertahankan pola makan dan datang ke posyandu setiap bulan.'];
        }

        if ($diff == 0.0) {
            return ['level' => 'warn', 'message' => 'Berat badan belum naik dibanding penimbangan sebelumnya. Tanyakan pola makan anak kepada kader pada posyandu berikutnya.'];
        }

        return ['level' => 'warn', 'message' => 'Berat badan turun '.self::kg(abs($diff)).' dibanding penimbangan sebelumnya. Sebaiknya konsultasikan dengan kader atau tenaga kesehatan.'];
    }

    private static function kg(float $value): string
    {
        return rtrim(rtrim(number_format($value, 2, ',', '.'), '0'), ',').' kg';
    }
}
