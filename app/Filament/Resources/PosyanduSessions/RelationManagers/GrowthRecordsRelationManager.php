<?php

namespace App\Filament\Resources\PosyanduSessions\RelationManagers;

use App\Models\PosyanduSession;
use App\Models\Resident;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;

/**
 * Children weighed on this posyandu day.
 */
class GrowthRecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'growthRecords';

    protected static ?string $title = 'Penimbangan Anak';

    protected static ?string $modelLabel = 'penimbangan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('resident_id')
                    ->label('Anak')
                    ->options(fn () => Resident::query()->toddlers()->with('household.neighborhood')->orderBy('name')->get()
                        ->mapWithKeys(fn (Resident $r) => [$r->id => $r->name.' ('.$r->household->neighborhood->label.')']))
                    ->searchable()
                    ->required()
                    ->unique(
                        table: 'growth_records',
                        column: 'resident_id',
                        ignoreRecord: true,
                        modifyRuleUsing: fn (Unique $rule, RelationManager $livewire) => $rule->where('posyandu_session_id', $livewire->getOwnerRecord()->getKey()),
                    )
                    ->validationMessages(['unique' => 'Anak ini sudah ditimbang pada kegiatan ini.'])
                    ->columnSpanFull(),
                TextInput::make('weight_kg')->label('Berat badan (kg)')->numeric()->required()->minValue(0.5)->maxValue(150)->step(0.01),
                TextInput::make('height_cm')->label('Tinggi / panjang badan (cm)')->numeric()->minValue(20)->maxValue(220)->step(0.1),
                TextInput::make('head_circumference_cm')->label('Lingkar kepala (cm)')->numeric()->minValue(10)->maxValue(80)->step(0.1),
                TextInput::make('arm_circumference_cm')->label('Lingkar lengan (cm)')->numeric()->minValue(5)->maxValue(60)->step(0.1),
                Textarea::make('notes')->label('Catatan')->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('resident'))
            ->columns([
                TextColumn::make('resident.name')->label('Anak')->searchable(),
                TextColumn::make('age_in_months')->label('Usia (bln)')->sortable(),
                TextColumn::make('weight_kg')->label('BB (kg)'),
                TextColumn::make('height_cm')->label('TB (cm)')->placeholder('-'),
                TextColumn::make('head_circumference_cm')->label('LK (cm)')->placeholder('-')->toggleable(),
                TextColumn::make('arm_circumference_cm')->label('LiLA (cm)')->placeholder('-')->toggleable(),
                TextColumn::make('bmi')->label('BMI')->placeholder('-'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data, RelationManager $livewire): array {
                        /** @var PosyanduSession $session */
                        $session = $livewire->getOwnerRecord();
                        $data['measured_on'] = $session->held_on;
                        $data['measured_by'] = auth()->id();

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
