<?php

namespace App\Filament\Resources\Households\RelationManagers;

use App\Enums\Gender;
use App\Enums\Relationship;
use App\Enums\ResidentStatus;
use App\Models\Resident;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Family members (head, spouse, children, ...) that live in the household.
 */
class ResidentsRelationManager extends RelationManager
{
    protected static string $relationship = 'residents';

    protected static ?string $title = 'Anggota Keluarga';

    protected static ?string $modelLabel = 'anggota keluarga';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('relationship')
                    ->label('Hubungan dalam keluarga')
                    ->options(Relationship::class)
                    ->required(),
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
                    ->options(fn (RelationManager $livewire) => $livewire->getOwnerRecord()->residents()
                        ->where('gender', Gender::Male->value)->pluck('name', 'id'))
                    ->searchable(),
                Select::make('mother_id')
                    ->label('Ibu')
                    ->options(fn (RelationManager $livewire) => $livewire->getOwnerRecord()->residents()
                        ->where('gender', Gender::Female->value)->pluck('name', 'id'))
                    ->searchable(),
                TextInput::make('occupation')->label('Pekerjaan')->maxLength(255),
                TextInput::make('phone')->label('Telepon')->tel()->maxLength(20),
                TextInput::make('health_insurance_number')->label('No. BPJS / JKN')->maxLength(255),
                Select::make('status')->label('Status')->options(ResidentStatus::class)->default(ResidentStatus::Active->value)->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable(),
                TextColumn::make('relationship')->label('Hubungan')->badge(),
                TextColumn::make('gender')->label('L/P')->badge(),
                TextColumn::make('birth_date')->label('Tgl. lahir')->date('d M Y')->placeholder('-'),
                TextColumn::make('age')
                    ->label('Usia')
                    ->state(fn (Resident $record) => $record->ageInMonths() === null
                        ? '-'
                        : intdiv($record->ageInMonths(), 12).' th '.($record->ageInMonths() % 12).' bln'),
                TextColumn::make('status')->label('Status')->badge(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
