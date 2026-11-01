<?php

namespace App\Http\Controllers;

use App\Models\Cadre;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CadreController extends Controller
{
    public function index(): View
    {
        $cadres = Cadre::all();

        return view('Admin.Pages.DataKader.index', compact('cadres'));
    }

    public function store(Request $request): RedirectResponse
    {
        Cadre::create($this->validated($request));

        return redirect('/data-kader')->with('success', 'Data Kader berhasil ditambahkan!');
    }

    public function edit(int $id): View
    {
        $cadre = Cadre::findOrFail($id);

        return view('Admin.Pages.DataKader.update', compact('cadre'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        Cadre::findOrFail($id)->update($this->validated($request));

        return redirect('/data-kader')->with('success', 'Data Kader berhasil diubah!');
    }

    public function destroy(int $id): RedirectResponse
    {
        Cadre::findOrFail($id)->delete();

        return redirect('/data-kader')->with('success', 'Data Kader berhasil dihapus!');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);
    }
}
