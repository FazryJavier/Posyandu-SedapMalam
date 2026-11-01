<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Guardian;
use Illuminate\Contracts\View\View;

class WeighingController extends Controller
{
    public function index(): View
    {
        $children = Child::all();
        $guardians = Guardian::all();

        return view('Admin.Pages.TimbangAnak.index', compact('children', 'guardians'));
    }
}
