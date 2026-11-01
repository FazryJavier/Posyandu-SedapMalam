<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Relationship: string implements HasLabel
{
    case Head = 'head';
    case Spouse = 'spouse';
    case Child = 'child';
    case Parent = 'parent';
    case Sibling = 'sibling';
    case Other = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::Head => 'Kepala Keluarga',
            self::Spouse => 'Suami / Istri',
            self::Child => 'Anak',
            self::Parent => 'Orang Tua',
            self::Sibling => 'Saudara',
            self::Other => 'Lainnya',
        };
    }
}
