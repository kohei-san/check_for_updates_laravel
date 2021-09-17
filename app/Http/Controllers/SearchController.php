<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Kyslik\ColumnSortable\Sortable;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('search');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function result(Request $request)
    {
        $customers = Customer::search($request->searchword)
                            ->where('active_flg', 1)
                            ->where('del_flg', 0)
                            ->paginate(50);

        return view('search')->with('customers', $customers);
    }
}
