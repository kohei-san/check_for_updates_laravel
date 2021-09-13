<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomePage;
use App\Models\PageHtml;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::with(['customer_page', 'page_html'])->paginate(50);

        return view('customer')->with('customers', $customers);
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
