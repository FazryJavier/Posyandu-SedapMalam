<?php

namespace App\Http\Controllers;

use App\Models\DataAnak;
use App\Models\DataKader;
use App\Models\DataOrangtua;
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
                'anak' => DataAnak::count(),
                'keluarga' => DataOrangtua::count(),
                'kader' => DataKader::count(),
            ];
        } catch (Throwable $e) {
            Log::warning('Statistik landing page tidak tersedia: '.$e->getMessage());

            return null;
        }
    }
}
