<?php

namespace App\Http\Controllers;

use App\Models\Html;
use Illuminate\Http\Request;

class HtmlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $timestamps = Html::paginate(50);
        $timestamps = Html::with(['page', 'customer'])->find(1);

        dd($timestamps->customer());

        return view('timestamp')->with('timestamps', $timestamps);
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
     * @param  \App\Models\PageHtml  $pageHtml
     * @return \Illuminate\Http\Response
     */
    public function show(PageHtml $pageHtml)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PageHtml  $pageHtml
     * @return \Illuminate\Http\Response
     */
    public function edit(PageHtml $pageHtml)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PageHtml  $pageHtml
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PageHtml $pageHtml)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PageHtml  $pageHtml
     * @return \Illuminate\Http\Response
     */
    public function destroy(PageHtml $pageHtml)
    {
        //
    }
}
