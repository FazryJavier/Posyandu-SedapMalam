<?php

namespace App\Http\Controllers;

use App\Models\DataKader;
use Illuminate\Http\Request;

class DataKaderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataKader = DataKader::all();

        return view('Admin.Pages.DataKader.index', compact('dataKader'));
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
            'nama_kader' => 'required',
            'jabatan' => 'required',
            'alamat' => 'required',
            'no_telpon' => 'required',
        ]);

        DataKader::create($validatedData);

        return redirect('/data-kader')->with('success', 'Data Kader berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(DataKader $dataKader)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dataKader = DataKader::where('id', $id)->firstorfail();

        return view('Admin.Pages.DataKader.update', compact('dataKader'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_kader' => 'required',
            'jabatan' => 'required',
            'alamat' => 'required',
            'no_telpon' => 'required',
        ]);

        $dataKader = DataKader::findOrFail($id);
        $dataKader->update($validatedData);

        return redirect('/data-kader')->with('success', 'Data Kader berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $dataKader = DataKader::findOrFail($id);
        $dataKader->delete();

        return redirect('/data-kader')->with('success', 'Data Kader berhasil dihapus!');
    }
}
