<?php

namespace App\Http\Controllers;

use App\Models\DataAnak;
use App\Models\DataKader;
use App\Models\DataOrangtua;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataOrangtua = DataOrangtua::all();
        $dataOrangtuaCount = $dataOrangtua->count();

        $dataAnak = DataAnak::all();
        $dataAnakCount = $dataAnak->count();

        $dataKader = DataKader::all();
        $dataKaderCount = $dataKader->count();

        return view('Admin.dashboard', compact('dataOrangtuaCount', 'dataAnakCount', 'dataKaderCount'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
