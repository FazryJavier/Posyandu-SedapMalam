<?php

namespace App\Filament\Resources\Residents\RelationManagers;

use App\Models\PosyanduSession;
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
 * Month-by-month measurements of one child.
 */
class GrowthRecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'growthRecords';

    protected static ?string $title = 'Riwayat Pertumbuhan';

    protected static ?string $modelLabel = 'pengukuran';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('posyandu_session_id')
                    ->label('Kegiatan Posyandu')
                    ->options(fn () => PosyanduSession::query()->orderByDesc('held_on')->get()
                        ->mapWithKeys(fn (PosyanduSession $s) => [$s->id => $s->held_on->translatedFormat('d F Y').($s->title ? ' - '.$s->title : '')]))
                    ->required()
                    ->searchable()
                    ->unique(
                        table: 'growth_records',
                        column: 'posyandu_session_id',
                        ignoreRecord: true,
                        modifyRuleUsing: fn (Unique $rule, RelationManager $livewire) => $rule->where('resident_id', $livewire->getOwnerRecord()->getKey()),
                    )
                    ->validationMessages(['unique' => 'Anak ini sudah diukur pada kegiatan tersebut.'])
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
            ->modifyQueryUsing(fn ($query) => $query->with('session'))
            ->defaultSort('measured_on', 'desc')
            ->columns([
                TextColumn::make('measured_on')->label('Tanggal')->date('d M Y')->sortable(),
                TextColumn::make('age_in_months')->label('Usia (bln)')->sortable(),
                TextColumn::make('weight_kg')->label('BB (kg)'),
                TextColumn::make('height_cm')->label('TB (cm)')->placeholder('-'),
                TextColumn::make('head_circumference_cm')->label('LK (cm)')->placeholder('-')->toggleable(),
                TextColumn::make('arm_circumference_cm')->label('LiLA (cm)')->placeholder('-')->toggleable(),
                TextColumn::make('bmi')->label('BMI')->placeholder('-'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $session = PosyanduSession::find($data['posyandu_session_id']);
                        $data['measured_on'] = $session?->held_on ?? now();
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
