<?php

namespace App\Filament\Resources\Cadres;

use App\Enums\CadreTier;
use App\Filament\Resources\Cadres\Pages\CreateCadre;
use App\Filament\Resources\Cadres\Pages\EditCadre;
use App\Filament\Resources\Cadres\Pages\ListCadres;
use App\Models\Cadre;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class CadreResource extends Resource
{
    protected static ?string $model = Cadre::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static string|UnitEnum|null $navigationGroup = 'Internal';

    protected static ?string $navigationLabel = 'Kader';

    protected static ?string $modelLabel = 'Kader';

    protected static ?string $pluralModelLabel = 'Kader';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')->label('Nama')->required()->maxLength(255),
                TextInput::make('position')->label('Jabatan')->required()->maxLength(255),
                Select::make('tier')
                    ->label('Tingkat pada organigram')
                    ->options(CadreTier::class)
                    ->default(CadreTier::Section->value)
                    ->required(),
                TextInput::make('sort_order')->label('Urutan tampil')->numeric()->default(0)->minValue(0),
                TextInput::make('phone')->label('Telepon')->tel()->maxLength(20),
                DatePicker::make('joined_on')->label('Mulai bertugas'),
                Select::make('user_id')
                    ->label('Akun login (opsional)')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->unique(ignoreRecord: true),
                Textarea::make('address')->label('Alamat')->columnSpanFull(),
                Toggle::make('is_active')->label('Masih aktif')->default(true),
                Toggle::make('show_on_website')->label('Tampilkan di website')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable(),
                TextColumn::make('position')->label('Jabatan')->searchable(),
                TextColumn::make('tier')->label('Tingkat')->badge(),
                TextColumn::make('phone')->label('Telepon')->placeholder('-'),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
                IconColumn::make('show_on_website')->label('Di website')->boolean(),
                TextColumn::make('sort_order')->label('Urutan')->sortable(),
            ])
            ->filters([
                SelectFilter::make('tier')->label('Tingkat')->options(CadreTier::class),
                TernaryFilter::make('is_active')->label('Aktif'),
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

    public static function getPages(): array
    {
        return [
            'index' => ListCadres::route('/'),
            'create' => CreateCadre::route('/create'),
            'edit' => EditCadre::route('/{record}/edit'),
        ];
    }
}
