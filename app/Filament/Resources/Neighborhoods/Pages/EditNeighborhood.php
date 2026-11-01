<?php

namespace App\Filament\Resources\Neighborhoods\Pages;

use App\Filament\Resources\Neighborhoods\NeighborhoodResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNeighborhood extends EditRecord
{
    protected static string $resource = NeighborhoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
