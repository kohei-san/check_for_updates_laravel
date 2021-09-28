<?php

namespace App\Http\Controllers;

use App\Models\LineRegister;
use Illuminate\Http\Request;
use App\Models\Customer;

class LineRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LineRegister  $lineRegister
     * @return \Illuminate\Http\Response
     */
    public function show(LineRegister $lineRegister)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LineRegister  $lineRegister
     * @return \Illuminate\Http\Response
     */
    public function edit(LineRegister $lineRegister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LineRegister  $lineRegister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LineRegister $lineRegister)
    {
        dd($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LineRegister  $lineRegister
     * @return \Illuminate\Http\Response
     */
    public function destroy(LineRegister $lineRegister)
    {
        //
    }
}
