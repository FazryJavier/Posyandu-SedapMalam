<?php

namespace App\Filament\Resources\PosyanduSessions\Pages;

use App\Filament\Resources\PosyanduSessions\PosyanduSessionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPosyanduSession extends EditRecord
{
    protected static string $resource = PosyanduSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
