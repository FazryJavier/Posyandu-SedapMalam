<?php

namespace App\Http\Controllers;

use App\Models\GrafikPerkembangan;
use Illuminate\Http\Request;

class GrafikPerkembanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Admin.Pages.GrafikPerkembangan.index');
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
    public function show(GrafikPerkembangan $grafikPerkembangan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GrafikPerkembangan $grafikPerkembangan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GrafikPerkembangan $grafikPerkembangan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GrafikPerkembangan $grafikPerkembangan)
    {
        //
    }
}
