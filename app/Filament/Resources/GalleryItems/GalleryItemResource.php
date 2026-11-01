<?php

namespace App\Filament\Resources\GalleryItems;

use App\Filament\Resources\GalleryItems\Pages\CreateGalleryItem;
use App\Filament\Resources\GalleryItems\Pages\EditGalleryItem;
use App\Filament\Resources\GalleryItems\Pages\ListGalleryItems;
use App\Models\GalleryItem;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class GalleryItemResource extends Resource
{
    protected static ?string $model = GalleryItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|UnitEnum|null $navigationGroup = 'Konten Website';

    protected static ?string $navigationLabel = 'Galeri Foto';

    protected static ?string $modelLabel = 'Foto';

    protected static ?string $pluralModelLabel = 'Galeri Foto';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image_path')
                    ->label('Foto')
                    ->image()
                    ->disk('public')
                    ->directory('gallery')
                    ->visibility('public')
                    ->maxSize(4096)
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('title')->label('Judul / keterangan')->maxLength(255),
                TextInput::make('sort_order')->label('Urutan tampil')->numeric()->default(0)->minValue(0),
                Toggle::make('is_published')->label('Tampilkan di website')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('image_path')->label('Foto')->disk('public')->square(),
                TextColumn::make('title')->label('Judul')->searchable()->placeholder('-'),
                TextColumn::make('sort_order')->label('Urutan')->sortable(),
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
            'index' => ListGalleryItems::route('/'),
            'create' => CreateGalleryItem::route('/create'),
            'edit' => EditGalleryItem::route('/{record}/edit'),
        ];
    }
}
