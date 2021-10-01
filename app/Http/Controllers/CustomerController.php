<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerPage;
use App\Models\PageHtml;
use App\Models\CreateHtml;
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
        $customerPages = CustomerPage::with(['customer', 'page_html', 'short_diff'])
                            ->where('customer_id', $customer_id)
                            ->get();

        $htmlPreDirsModel = CreateHtml::orderBy("create_html_id", 'desc')->limit(3)->get();
        foreach ($htmlPreDirsModel as $key => $htmlPreDirmodel){
            $htmlPreDir[$key] = "Http\Controllers\\python\\acquired_data\\" . $htmlPreDirmodel["filename_timestamp"] ."\\html\\";
        }

        $htmlShortDifDir = __DIR__ . "\\python\\different\\short_term\\";

        return view('show-customer')->with('customerPages', $customerPages)
                                    ->with('htmlPreDir', $htmlPreDir)
                                    ->with('htmlShortDifDir', $htmlShortDifDir);
    }
}
