<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class GrowthChartController extends Controller
{
    public function index(): View
    {
        return view('Admin.Pages.GrafikPerkembangan.index');
    }
}
