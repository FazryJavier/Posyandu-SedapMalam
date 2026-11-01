<?php

namespace App\Filament\Resources\Programs;

use App\Filament\Resources\Programs\Pages\CreateProgram;
use App\Filament\Resources\Programs\Pages\EditProgram;
use App\Filament\Resources\Programs\Pages\ListPrograms;
use App\Models\Program;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ProgramResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Konten Website';

    protected static ?string $navigationLabel = 'Program Kerja';

    protected static ?string $modelLabel = 'Program';

    protected static ?string $pluralModelLabel = 'Program Kerja';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')->label('Judul')->required()->maxLength(255),
                TextInput::make('icon')
                    ->label('Ikon (kelas Boxicons)')
                    ->helperText('Contoh: bx bx-heart, bx bx-injection, bx bx-group')
                    ->default('bx bx-heart')
                    ->required()
                    ->maxLength(50),
                Textarea::make('description')->label('Deskripsi')->required()->rows(4)->columnSpanFull(),
                TextInput::make('sort_order')->label('Urutan tampil')->numeric()->default(0)->minValue(0),
                Toggle::make('is_published')->label('Tampilkan di website')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('Urutan')->sortable(),
                TextColumn::make('title')->label('Judul')->searchable(),
                TextColumn::make('description')->label('Deskripsi')->limit(70),
                IconColumn::make('is_published')->label('Tampil')->boolean(),
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
            'index' => ListPrograms::route('/'),
            'create' => CreateProgram::route('/create'),
            'edit' => EditProgram::route('/{record}/edit'),
        ];
    }
}
