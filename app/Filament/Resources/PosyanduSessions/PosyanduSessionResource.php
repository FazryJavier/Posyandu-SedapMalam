<?php

namespace App\Filament\Resources\PosyanduSessions;

use App\Filament\Resources\PosyanduSessions\Pages\CreatePosyanduSession;
use App\Filament\Resources\PosyanduSessions\Pages\EditPosyanduSession;
use App\Filament\Resources\PosyanduSessions\Pages\ListPosyanduSessions;
use App\Filament\Resources\PosyanduSessions\RelationManagers\GrowthRecordsRelationManager;
use App\Models\PosyanduSession;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class PosyanduSessionResource extends Resource
{
    protected static ?string $model = PosyanduSession::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|UnitEnum|null $navigationGroup = 'Kegiatan Posyandu';

    protected static ?string $navigationLabel = 'Kegiatan Posyandu';

    protected static ?string $modelLabel = 'Kegiatan';

    protected static ?string $pluralModelLabel = 'Kegiatan Posyandu';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                DatePicker::make('held_on')->label('Tanggal')->required()->default(now()),
                TextInput::make('title')->label('Judul')->placeholder('Posyandu bulan November')->maxLength(255),
                TextInput::make('location')->label('Lokasi')->maxLength(255)->columnSpanFull(),
                Textarea::make('notes')->label('Catatan')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('held_on', 'desc')
            ->columns([
                TextColumn::make('held_on')->label('Tanggal')->date('d F Y')->sortable(),
                TextColumn::make('title')->label('Judul')->searchable()->placeholder('-'),
                TextColumn::make('location')->label('Lokasi')->placeholder('-'),
                TextColumn::make('growth_records_count')->label('Anak ditimbang')->counts('growthRecords')->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            GrowthRecordsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosyanduSessions::route('/'),
            'create' => CreatePosyanduSession::route('/create'),
            'edit' => EditPosyanduSession::route('/{record}/edit'),
        ];
    }
}
