<?php

namespace App\Support;

/**
 * Builds the coordinates for a tiny server-rendered SVG line chart.
 *
 * The family page deliberately uses plain SVG instead of a JavaScript chart
 * library: it works on very old phones, needs no third-party request (which
 * could leak the private link) and prints well.
 */
class SvgLineChart
{
    public const WIDTH = 320;

    public const HEIGHT = 150;

    private const PAD_X = 28;

    private const PAD_TOP = 24;

    private const PAD_BOTTOM = 30;

    /**
     * @param  array<int, float|null>  $values  one value per measurement, oldest first
     * @param  array<int, string>  $labels  short x-axis labels, same length as $values
     * @return array{points: string, dots: array<int, array{x: float, y: float, value: float, label: string}>, min: float, max: float}|null
     */
    public static function build(array $values, array $labels): ?array
    {
        $series = [];
        foreach ($values as $index => $value) {
            if ($value !== null) {
                $series[] = ['index' => $index, 'value' => (float) $value, 'label' => $labels[$index] ?? ''];
            }
        }

        if (count($series) < 2) {
            return null;
        }

        $min = min(array_column($series, 'value'));
        $max = max(array_column($series, 'value'));
        // Keep the vertical scale honest: a 0.1 kg wobble must not look like a collapse,
        // so the axis always spans at least 10% of the largest value.
        $range = max($max - $min, $max * 0.1) ?: 1.0;
        $low = ($max + $min) / 2 - $range / 2;

        $innerWidth = self::WIDTH - 2 * self::PAD_X;
        $innerHeight = self::HEIGHT - self::PAD_TOP - self::PAD_BOTTOM;
        $last = count($series) - 1;

        $dots = [];
        foreach ($series as $position => $point) {
            $dots[] = [
                'x' => round(self::PAD_X + ($innerWidth * $position / $last), 1),
                'y' => round(self::PAD_TOP + $innerHeight * (1 - ($point['value'] - $low) / $range), 1),
                'value' => $point['value'],
                'label' => $point['label'],
            ];
        }

        return [
            'points' => implode(' ', array_map(fn (array $d) => $d['x'].','.$d['y'], $dots)),
            'dots' => $dots,
            'min' => $min,
            'max' => $max,
        ];
    }
}
