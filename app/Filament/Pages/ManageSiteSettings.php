<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use App\Support\SiteContent;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * CMS form for the text shown on the public landing page.
 *
 * @property-read Schema $form
 */
class ManageSiteSettings extends Page
{
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Konten Website';

    protected static ?string $navigationLabel = 'Pengaturan Website';

    protected static ?string $title = 'Pengaturan Website';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.manage-site-settings';

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(SiteContent::formDefaults());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Beranda')
                    ->schema([
                        TextInput::make('tagline')->label('Tagline di halaman utama')->required()->maxLength(255),
                        TextInput::make('schedule')
                            ->label('Jadwal posyandu')
                            ->placeholder('Setiap Sabtu minggu ke-2, pukul 08.00 - 11.00 WIB')
                            ->maxLength(255),
                    ]),
                Section::make('Tentang Kami')
                    ->schema([
                        Repeater::make('about')
                            ->label('Paragraf tentang posyandu')
                            ->simple(Textarea::make('text')->required()->rows(3))
                            ->addActionLabel('Tambah paragraf')
                            ->reorderable(),
                        Repeater::make('target_audience')
                            ->label('Sasaran layanan')
                            ->simple(TextInput::make('text')->required())
                            ->addActionLabel('Tambah sasaran'),
                        Textarea::make('vision')->label('Visi')->rows(3),
                        Repeater::make('mission')
                            ->label('Misi')
                            ->simple(TextInput::make('text')->required())
                            ->addActionLabel('Tambah misi')
                            ->reorderable(),
                    ]),
                Section::make('Kontak dan Lokasi')
                    ->columns(2)
                    ->schema([
                        Textarea::make('address')->label('Alamat')->rows(2)->columnSpanFull(),
                        TextInput::make('phone')->label('Telepon')->tel()->maxLength(20),
                        TextInput::make('whatsapp')->label('WhatsApp')->tel()->maxLength(20),
                        TextInput::make('email')->label('Email')->email()->maxLength(255),
                        TextInput::make('map_query')
                            ->label('Kata kunci Google Maps')
                            ->helperText('Dipakai untuk tombol Petunjuk Arah.')
                            ->maxLength(255),
                    ]),
            ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach (SiteContent::KEYS as $key) {
            SiteSetting::put($key, $state[$key] ?? null);
        }

        Notification::make()->title('Pengaturan website disimpan')->success()->send();
    }
}
