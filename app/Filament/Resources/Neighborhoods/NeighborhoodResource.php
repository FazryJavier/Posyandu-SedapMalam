<?php

namespace App\Filament\Resources\Neighborhoods;

use App\Filament\Resources\Neighborhoods\Pages\CreateNeighborhood;
use App\Filament\Resources\Neighborhoods\Pages\EditNeighborhood;
use App\Filament\Resources\Neighborhoods\Pages\ListNeighborhoods;
use App\Models\Neighborhood;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;
use UnitEnum;

class NeighborhoodResource extends Resource
{
    protected static ?string $model = Neighborhood::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static string|UnitEnum|null $navigationGroup = 'Data Warga';

    protected static ?string $navigationLabel = 'RT / RW';

    protected static ?string $modelLabel = 'RT / RW';

    protected static ?string $pluralModelLabel = 'RT / RW';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'rt';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('rw')
                    ->label('RW')
                    ->required()
                    ->maxLength(3)
                    ->default('11'),
                TextInput::make('rt')
                    ->label('RT')
                    ->required()
                    ->maxLength(3)
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: fn (Unique $rule, callable $get) => $rule->where('rw', $get('rw')),
                    ),
                TextInput::make('head_name')
                    ->label('Ketua RT')
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label('Telepon')
                    ->tel()
                    ->maxLength(20),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rw')->label('RW')->sortable()->searchable(),
                TextColumn::make('rt')->label('RT')->sortable()->searchable(),
                TextColumn::make('head_name')->label('Ketua RT')->searchable()->placeholder('-'),
                TextColumn::make('phone')->label('Telepon')->placeholder('-'),
                TextColumn::make('households_count')->label('Jumlah KK')->counts('households')->sortable(),
            ])
            ->defaultSort('rt')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNeighborhoods::route('/'),
            'create' => CreateNeighborhood::route('/create'),
            'edit' => EditNeighborhood::route('/{record}/edit'),
        ];
    }
}
