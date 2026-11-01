<?php

namespace App\Http\Controllers;

use App\Models\Cadre;
use App\Models\Household;
use App\Models\Resident;
use App\Support\SiteContent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Throwable;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('User.home', [
            'posyandu' => SiteContent::landing(),
            'stats' => $this->stats(),
        ]);
    }

    /**
     * Ringkasan data untuk landing page. Mengembalikan null bila database
     * tidak dapat dijangkau agar halaman publik tetap tampil.
     *
     * @return array<string, int>|null
     */
    private function stats(): ?array
    {
        try {
            return [
                'children' => Resident::toddlers()->count(),
                'households' => Household::count(),
                'cadres' => Cadre::where('is_active', true)->count(),
            ];
        } catch (Throwable $e) {
            Log::warning('Statistik landing page tidak tersedia: '.$e->getMessage());

            return null;
        }
    }
}
