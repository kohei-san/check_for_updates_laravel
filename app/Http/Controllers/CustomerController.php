<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerPage;
use App\Models\PageHtml;
use Illuminate\Http\Request;
use Kyslik\ColumnSortable\Sortable;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::with('page_html', 'line_register')
                            // ->whereHas('page_html', function($query){
                            //     $query->where('line_flg', 1);
                            // })
                            ->where('active_flg', 1)
                            ->where('del_flg', 0)
                            ->sortable()
                            ->paginate(50);

        return view('customer')->with('customers', $customers);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer_id = $id;
        // $customer_pages = CustomerPage::where('customer_id', $customer_id)->get();
        $customer_pages = CustomerPage::with(['customer', 'page_html'])
                            ->where('customer_id', $customer_id)
                            ->get();

        return view('show-customer')->with('customer_pages', $customer_pages);
    }
}
