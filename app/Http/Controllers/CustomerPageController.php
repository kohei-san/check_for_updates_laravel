<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerPage;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Customer;
use Carbon\Carbon;

class CustomerPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customerPages = CustomerPage::with(['customer', 'short_diff', 'line_register'])
                                        ->whereHas('Customer', function($query){
                                            $query->where('active_flg', 1)
                                                ->where('del_flg', 0);
                                        })
                                        ->where('top_page_flg', 1)
                                        ->sortable()
                                        ->paginate(50);

        $htmlDir = __DIR__ . "\\python\\different\\short_term\\";            

        return view('customer-page')->with('customerPages', $customerPages)
                                    ->with('htmlDir', $htmlDir);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerPage  $customerPage
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerPage $customerPage)
    {
        //
    }
}
