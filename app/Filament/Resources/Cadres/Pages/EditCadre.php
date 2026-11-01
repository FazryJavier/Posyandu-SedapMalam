<?php

namespace App\Filament\Resources\Cadres\Pages;

use App\Filament\Resources\Cadres\CadreResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCadre extends EditRecord
{
    protected static string $resource = CadreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
