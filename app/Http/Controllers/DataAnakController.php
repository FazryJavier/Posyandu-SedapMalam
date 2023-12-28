<?php

namespace App\Http\Controllers;

use App\Models\DataAnak;
use App\Models\DataOrangtua;
use Illuminate\Http\Request;

class DataAnakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataAnak = DataAnak::orderBy('created_at', 'asc')->get();
        $dataOrangtua = DataOrangtua::all();

        return view('Admin.Pages.DataAnak.index', compact('dataAnak', 'dataOrangtua'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nik_anak' => 'required',
            'nama_anak' => 'required',
            'umur' => 'required',
            'berat_badan' => 'nullable',
            'tinggi_badan' => 'nullable',
            'lingkar_kepala' => 'nullable',
            'lingkar_lengan' => 'nullable',
            'bmi' => 'nullable',
            'IdOrangtua' => 'required',
        ]);

        DataAnak::create($validatedData);

        return redirect('/data-anak')->with('success', 'Data Anak berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(DataAnak $dataAnak)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nik_anak)
    {
        $dataAnak = DataAnak::where('nik_anak', $nik_anak)->firstorfail();
        $dataOrangtua = DataOrangtua::all();

        return view('Admin.Pages.DataAnak.update', compact('dataAnak', 'dataOrangtua'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $nik_anak)
    {
        $validatedData = $request->validate([
            'nik_anak' => 'required',
            'nama_anak' => 'required',
            'umur' => 'required',
            'berat_badan' => 'nullable',
            'tinggi_badan' => 'nullable',
            'lingkar_kepala' => 'nullable',
            'lingkar_lengan' => 'nullable',
            'bmi' => 'nullable',
            'IdOrangtua' => 'required',
        ]);

        $dataAnak = DataAnak::findOrFail($nik_anak);
        $dataAnak->update($validatedData);

        return redirect('/data-anak')->with('success', 'Data Anak berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nik_anak)
    {
        $dataAnak = DataAnak::find($nik_anak);
        $dataAnak->delete();

        return redirect('/data-anak')->with('success', 'Data Anak berhasil dihapus!');
    }
}
