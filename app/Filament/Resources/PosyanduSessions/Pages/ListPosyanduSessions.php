<?php

namespace App\Filament\Resources\PosyanduSessions\Pages;

use App\Filament\Resources\PosyanduSessions\PosyanduSessionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPosyanduSessions extends ListRecords
{
    protected static string $resource = PosyanduSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
