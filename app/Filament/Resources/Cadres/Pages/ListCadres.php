<?php

namespace App\Filament\Resources\Cadres\Pages;

use App\Filament\Resources\Cadres\CadreResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCadres extends ListRecords
{
    protected static string $resource = CadreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
