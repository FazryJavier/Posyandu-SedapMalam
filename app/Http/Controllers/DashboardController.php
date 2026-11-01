<?php

namespace App\Http\Controllers;

use App\Models\Cadre;
use App\Models\Child;
use App\Models\Guardian;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $guardianCount = Guardian::count();
        $childCount = Child::count();
        $cadreCount = Cadre::count();

        return view('Admin.dashboard', compact('guardianCount', 'childCount', 'cadreCount'));
    }
}
