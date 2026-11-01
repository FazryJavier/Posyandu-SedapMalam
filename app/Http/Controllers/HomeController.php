<?php

namespace App\Http\Controllers;

use App\Models\Cadre;
use App\Models\Child;
use App\Models\Guardian;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Throwable;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('User.home', [
            'posyandu' => config('posyandu'),
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
                'children' => Child::count(),
                'guardians' => Guardian::count(),
                'cadres' => Cadre::count(),
            ];
        } catch (Throwable $e) {
            Log::warning('Statistik landing page tidak tersedia: '.$e->getMessage());

            return null;
        }
    }
}
