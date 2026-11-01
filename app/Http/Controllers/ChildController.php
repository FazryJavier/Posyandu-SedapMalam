<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Guardian;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChildController extends Controller
{
    public function index(): View
    {
        $children = Child::orderBy('created_at', 'asc')->get();
        $guardians = Guardian::all();

        return view('Admin.Pages.DataAnak.index', compact('children', 'guardians'));
    }

    public function store(Request $request): RedirectResponse
    {
        Child::create($this->validated($request));

        return redirect('/data-anak')->with('success', 'Data Anak berhasil ditambahkan!');
    }

    public function edit(string $nik): View
    {
        $child = Child::findOrFail($nik);
        $guardians = Guardian::all();

        return view('Admin.Pages.DataAnak.update', compact('child', 'guardians'));
    }

    public function update(Request $request, string $nik): RedirectResponse
    {
        $child = Child::findOrFail($nik);
        $child->update($this->validated($request, $child));

        return redirect('/data-anak')->with('success', 'Data Anak berhasil diubah!');
    }

    public function destroy(string $nik): RedirectResponse
    {
        Child::findOrFail($nik)->delete();

        return redirect('/data-anak')->with('success', 'Data Anak berhasil dihapus!');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Child $child = null): array
    {
        return $request->validate([
            'nik' => ['required', 'string', 'max:255', Rule::unique('children', 'nik')->ignore($child?->nik, 'nik')],
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'weight' => 'nullable|integer|min:0',
            'height' => 'nullable|integer|min:0',
            'head_circumference' => 'nullable|integer|min:0',
            'arm_circumference' => 'nullable|integer|min:0',
            'bmi' => 'nullable|integer|min:0',
            'guardian_id' => 'required|exists:guardians,id',
        ]);
    }
}
