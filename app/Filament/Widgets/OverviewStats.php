<?php

namespace App\Filament\Widgets;

use App\Models\GrowthRecord;
use App\Models\Household;
use App\Models\PosyanduSession;
use App\Models\Resident;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Headline numbers shown on the admin dashboard.
 */
class OverviewStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $latestSession = PosyanduSession::query()->latest('held_on')->first();

        return [
            Stat::make('Keluarga terdaftar', Household::count())
                ->description('Kartu Keluarga di seluruh RT')
                ->icon('heroicon-o-home-modern'),
            Stat::make('Balita aktif', Resident::toddlers()->count())
                ->description('Anak usia 0-5 tahun')
                ->icon('heroicon-o-users'),
            Stat::make('Ditimbang pada kegiatan terakhir', $latestSession ? GrowthRecord::where('posyandu_session_id', $latestSession->id)->count() : 0)
                ->description($latestSession ? $latestSession->held_on->translatedFormat('d F Y') : 'Belum ada kegiatan')
                ->icon('heroicon-o-scale'),
        ];
    }
}
