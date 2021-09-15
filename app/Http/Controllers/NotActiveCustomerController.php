<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Kyslik\ColumnSortable\Sortable;

class NotActiveCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::with('page_html')
                                ->where('active_flg', 0)
                                ->where('del_flg', 0)
                                ->sortable()->get();

        return view('not-active-customer')->with('customers', $customers);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }
}
