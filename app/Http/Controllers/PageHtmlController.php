<?php

namespace App\Http\Controllers;

use App\Models\PageHtml;
use Illuminate\Http\Request;
use Kyslik\ColumnSortable\Sortable;

class PageHtmlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $timestamps = Html::paginate(50);
        $pageHtmls = PageHtml::with(['customer_page', 'customer'])->sortable()->paginate(50);

        // dd($pageHtmls);

        return view('pagehtml')->with('pageHtmls', $pageHtmls);
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
}
