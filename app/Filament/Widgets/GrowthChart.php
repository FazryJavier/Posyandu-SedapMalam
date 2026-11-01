<?php

namespace App\Filament\Widgets;

use App\Models\Resident;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;

/**
 * Line chart of a child's BMI and weight per posyandu session.
 */
class GrowthChart extends ChartWidget
{
    protected static bool $isDiscovered = false;

    protected ?string $heading = 'Grafik Perkembangan (BMI dan Berat Badan)';

    protected int|string|array $columnSpan = 'full';

    public ?Model $record = null;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $records = $this->record instanceof Resident
            ? $this->record->growthRecords()->get()
            : collect();

        return [
            'datasets' => [
                [
                    'label' => 'BMI',
                    'data' => $records->map(fn ($r) => $r->bmi !== null ? (float) $r->bmi : null)->all(),
                    'borderColor' => '#0ea5e9',
                    'backgroundColor' => '#0ea5e9',
                    'yAxisID' => 'bmi',
                    'spanGaps' => true,
                ],
                [
                    'label' => 'Berat badan (kg)',
                    'data' => $records->map(fn ($r) => (float) $r->weight_kg)->all(),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => '#f59e0b',
                    'yAxisID' => 'weight',
                ],
            ],
            'labels' => $records->map(fn ($r) => $r->measured_on->format('M Y').' ('.$r->age_in_months.' bln)')->all(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'bmi' => ['type' => 'linear', 'position' => 'left', 'title' => ['display' => true, 'text' => 'BMI']],
                'weight' => ['type' => 'linear', 'position' => 'right', 'grid' => ['drawOnChartArea' => false], 'title' => ['display' => true, 'text' => 'kg']],
            ],
        ];
    }
}
