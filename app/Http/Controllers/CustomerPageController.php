<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerPage;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Customer;
use Carbon\Carbon;
use App\Models\DifferenceBetShortterm;
use App\Models\LongDifference;
use Illuminate\Support\Facades\DB;

class CustomerPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customerPages = CustomerPage::with(['customer', 'short_diff', 'line_register','long_diff'])
                                        ->whereHas('Customer', function($query){
                                            $query->where('active_flg', 1)
                                                ->where('del_flg', 0);
                                        })
                                        ->whereHas('long_diff',function(){
                                            LongDifference::selectRaw('count(customer_id) as customer_count, customer_id')
                                            ->groupBy('customer_id')
                                            ->where('difference_flg', 1)
                                            ->get();
                                        })
                                        ->where('top_page_flg', 1)
                                        ->sortable()
                                        ->paginate(50);

        return view('customer-page')->with('customerPages', $customerPages);
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
