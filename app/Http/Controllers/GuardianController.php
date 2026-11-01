<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GuardianController extends Controller
{
    public function index(): View
    {
        $guardians = Guardian::all();

        return view('Admin.Pages.DataOrangtua.index', compact('guardians'));
    }

    public function store(Request $request): RedirectResponse
    {
        Guardian::create($this->validated($request));

        return redirect('/data-orangtua')->with('success', 'Data Orang tua berhasil ditambahkan!');
    }

    public function edit(int $id): View
    {
        $guardian = Guardian::findOrFail($id);

        return view('Admin.Pages.DataOrangtua.update', compact('guardian'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        Guardian::findOrFail($id)->update($this->validated($request));

        return redirect('/data-orangtua')->with('success', 'Data Orang tua berhasil diubah!');
    }

    public function destroy(int $id): RedirectResponse
    {
        Guardian::findOrFail($id)->delete();

        return redirect('/data-orangtua')->with('success', 'Data Orang tua berhasil dihapus!');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);
    }
}
