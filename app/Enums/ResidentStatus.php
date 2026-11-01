<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ResidentStatus: string implements HasLabel
{
    case Active = 'active';
    case Moved = 'moved';
    case Deceased = 'deceased';

    public function getLabel(): string
    {
        return match ($this) {
            self::Active => 'Aktif',
            self::Moved => 'Pindah',
            self::Deceased => 'Meninggal',
        };
    }
}
