<?php

namespace App\Http\Controllers;

use App\Models\DataOrangtua;
use Illuminate\Http\Request;

class DataOrangtuaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataOrangtua = DataOrangtua::all();

        return view('Admin.Pages.DataOrangtua.index', compact('dataOrangtua'));
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
            'nama_ayah' => 'required',
            'nama_ibu' => 'required',
            'alamat' => 'required',
            'no_telpon' => 'required',
        ]);

        DataOrangtua::create($validatedData);

        return redirect('/data-orangtua')->with('success', 'Data Orang tua berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(DataOrangtua $dataOrangtua)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dataOrangtua = DataOrangtua::where('id', $id)->firstorfail();

        return view('Admin.Pages.DataOrangtua.update', compact('dataOrangtua'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_ayah' => 'required',
            'nama_ibu' => 'required',
            'alamat' => 'required',
            'no_telpon' => 'required',
        ]);

        $dataOrangtua = DataOrangtua::findOrFail($id);
        $dataOrangtua->update($validatedData);

        return redirect('/data-orangtua')->with('success', 'Data Orang tua berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $dataOrangtua = DataOrangtua::findOrFail($id);
        $dataOrangtua->delete();

        return redirect('/data-orangtua')->with('success', 'Data Orang tua berhasil dihapus!');
    }
}
