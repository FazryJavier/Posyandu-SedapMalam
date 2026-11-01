<?php

namespace App\Filament\Resources\Residents;

use App\Enums\Gender;
use App\Enums\Relationship;
use App\Enums\ResidentStatus;
use App\Filament\Resources\Residents\Pages\CreateResident;
use App\Filament\Resources\Residents\Pages\EditResident;
use App\Filament\Resources\Residents\Pages\ListResidents;
use App\Filament\Resources\Residents\RelationManagers\GrowthRecordsRelationManager;
use App\Models\Household;
use App\Models\Neighborhood;
use App\Models\Resident;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ResidentResource extends Resource
{
    protected static ?string $model = Resident::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|UnitEnum|null $navigationGroup = 'Data Warga';

    protected static ?string $navigationLabel = 'Warga';

    protected static ?string $modelLabel = 'Warga';

    protected static ?string $pluralModelLabel = 'Warga';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('household_id')
                    ->label('Keluarga (KK)')
                    ->relationship('household', 'family_card_number')
                    ->getOptionLabelFromRecordUsing(fn (Household $record) => trim(
                        ($record->head?->name ?? 'Tanpa kepala keluarga').' - '.$record->neighborhood->label
                    ))
                    ->searchable(['family_card_number', 'address'])
                    ->preload()
                    ->required()
                    ->columnSpanFull(),
                Select::make('relationship')->label('Hubungan dalam keluarga')->options(Relationship::class)->required(),
                TextInput::make('name')->label('Nama lengkap')->required()->maxLength(255),
                TextInput::make('nik')
                    ->label('NIK')
                    ->regex('/^\d{16}$/')
                    ->validationMessages(['regex' => 'NIK harus 16 digit angka.'])
                    ->unique(ignoreRecord: true)
                    ->maxLength(16),
                Select::make('gender')->label('Jenis kelamin')->options(Gender::class)->required(),
                DatePicker::make('birth_date')->label('Tanggal lahir')->maxDate(now()),
                TextInput::make('birth_place')->label('Tempat lahir')->maxLength(255),
                Select::make('father_id')
                    ->label('Ayah')
                    ->relationship('father', 'name', fn (Builder $query) => $query->where('gender', Gender::Male->value))
                    ->searchable()
                    ->preload(),
                Select::make('mother_id')
                    ->label('Ibu')
                    ->relationship('mother', 'name', fn (Builder $query) => $query->where('gender', Gender::Female->value))
                    ->searchable()
                    ->preload(),
                TextInput::make('occupation')->label('Pekerjaan')->maxLength(255),
                TextInput::make('phone')->label('Telepon')->tel()->maxLength(20),
                TextInput::make('health_insurance_number')->label('No. BPJS / JKN')->maxLength(255),
                Select::make('status')->label('Status')->options(ResidentStatus::class)->default(ResidentStatus::Active->value)->required(),
                Textarea::make('notes')->label('Catatan')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['household.neighborhood']))
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                TextColumn::make('nik')->label('NIK')->searchable()->placeholder('-')->toggleable(),
                TextColumn::make('household.neighborhood.rt')
                    ->label('RT / RW')
                    ->formatStateUsing(fn ($state, Resident $record) => $record->household->neighborhood->label),
                TextColumn::make('relationship')->label('Hubungan')->badge(),
                TextColumn::make('gender')->label('L/P')->badge(),
                TextColumn::make('birth_date')->label('Tgl. lahir')->date('d M Y')->sortable()->placeholder('-'),
                TextColumn::make('age_in_months')
                    ->label('Usia')
                    ->state(fn (Resident $record) => $record->ageInMonths() === null ? '-' : $record->ageInMonths().' bln'),
                TextColumn::make('status')->label('Status')->badge(),
            ])
            ->filters([
                Filter::make('toddlers')
                    ->label('Balita (0-5 tahun)')
                    ->query(fn (Builder $query) => $query->toddlers()),
                SelectFilter::make('relationship')->label('Hubungan')->options(Relationship::class),
                SelectFilter::make('neighborhood')
                    ->label('RT / RW')
                    ->options(fn () => Neighborhood::query()->orderBy('rw')->orderBy('rt')->get()->pluck('label', 'id'))
                    ->query(fn (Builder $query, array $data) => $query->when(
                        $data['value'] ?? null,
                        fn (Builder $q, $id) => $q->whereHas('household', fn (Builder $h) => $h->where('neighborhood_id', $id)),
                    )),
                SelectFilter::make('status')->label('Status')->options(ResidentStatus::class),
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
            GrowthRecordsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListResidents::route('/'),
            'create' => CreateResident::route('/create'),
            'edit' => EditResident::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
    }
}
