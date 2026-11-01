<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CadreTier: string implements HasLabel
{
    case Leader = 'leader';
    case Officer = 'officer';
    case Section = 'section';

    public function getLabel(): string
    {
        return match ($this) {
            self::Leader => 'Ketua',
            self::Officer => 'Pengurus',
            self::Section => 'Kader Seksi',
        };
    }
}
