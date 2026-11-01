<?php

namespace App\Filament\Resources\Households;

use App\Filament\Resources\Households\Pages\CreateHousehold;
use App\Filament\Resources\Households\Pages\EditHousehold;
use App\Filament\Resources\Households\Pages\ListHouseholds;
use App\Filament\Resources\Households\RelationManagers\ResidentsRelationManager;
use App\Models\Household;
use App\Models\Neighborhood;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class HouseholdResource extends Resource
{
    protected static ?string $model = Household::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHomeModern;

    protected static string|UnitEnum|null $navigationGroup = 'Data Warga';

    protected static ?string $navigationLabel = 'Keluarga (KK)';

    protected static ?string $modelLabel = 'Keluarga';

    protected static ?string $pluralModelLabel = 'Keluarga';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'family_card_number';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Keluarga')
                    ->columns(2)
                    ->schema([
                        Select::make('neighborhood_id')
                            ->label('RT / RW')
                            ->relationship('neighborhood', 'rt')
                            ->getOptionLabelFromRecordUsing(fn (Neighborhood $record) => $record->label)
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('family_card_number')
                            ->label('No. Kartu Keluarga')
                            ->regex('/^\d{16}$/')
                            ->validationMessages(['regex' => 'Nomor KK harus 16 digit angka.'])
                            ->unique(ignoreRecord: true)
                            ->maxLength(16),
                        Textarea::make('address')
                            ->label('Alamat')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('phone')
                            ->label('Telepon / WhatsApp')
                            ->tel()
                            ->maxLength(20),
                        TextInput::make('health_insurance')
                            ->label('Jaminan Kesehatan')
                            ->placeholder('JKN-KIS, swasta, tidak ada')
                            ->maxLength(255),
                    ]),
                Section::make('Kondisi Lingkungan')
                    ->description('Faktor yang berpengaruh pada kesehatan dan stunting anak.')
                    ->columns(3)
                    ->schema([
                        Toggle::make('has_clean_water')->label('Air bersih tersedia'),
                        Toggle::make('has_sanitary_toilet')->label('Jamban sehat'),
                        Toggle::make('receives_social_assistance')->label('Menerima bantuan sosial'),
                        Textarea::make('notes')->label('Catatan')->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['neighborhood', 'head'])->withCount('residents'))
            ->columns([
                TextColumn::make('neighborhood.rt')
                    ->label('RT / RW')
                    ->formatStateUsing(fn ($state, Household $record) => $record->neighborhood->label)
                    ->sortable(),
                TextColumn::make('head.name')->label('Kepala Keluarga')->searchable()->placeholder('Belum diisi'),
                TextColumn::make('family_card_number')->label('No. KK')->searchable()->placeholder('-'),
                TextColumn::make('residents_count')->label('Anggota')->sortable(),
                IconColumn::make('has_clean_water')->label('Air bersih')->boolean(),
                IconColumn::make('has_sanitary_toilet')->label('Jamban sehat')->boolean(),
                IconColumn::make('receives_social_assistance')->label('Bansos')->boolean(),
            ])
            ->filters([
                SelectFilter::make('neighborhood_id')
                    ->label('RT / RW')
                    ->options(fn () => Neighborhood::query()->orderBy('rw')->orderBy('rt')->get()->pluck('label', 'id')),
                TernaryFilter::make('has_sanitary_toilet')->label('Jamban sehat'),
                TernaryFilter::make('receives_social_assistance')->label('Bansos'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ResidentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHouseholds::route('/'),
            'create' => CreateHousehold::route('/create'),
            'edit' => EditHousehold::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
    }
}
