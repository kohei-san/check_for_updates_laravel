<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerPage;
use App\Models\PageHtml;
use App\Models\CreateHtml;
use App\Models\LongDifference;
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
        // $customers = Customer::with(['line_register','long_diff'])
        //                             ->where('active_flg', 1)
        //                             ->where('del_flg', 0)
        //                             ->sortable()
        //                             ->paginate(50);

        // return view('customer')->with('customers', $customers);
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
        $customerPages = CustomerPage::with(['customer', 'page_html', 'short_diff'])
                            ->where('customer_id', $customer_id)
                            ->get();

        $htmlPreModels = CreateHtml::orderBy("create_html_id", 'desc')->limit(3)->get();
        
        foreach ($htmlPreModels as $key => $htmlPreModel){
            $htmlPreDirs['full'][$key] = "Http/Controllers/python/acquired_data/" . $htmlPreModel["filename_timestamp"] ."/html/";
            $htmlPreDirs['filename'][$key] = $htmlPreModel["filename_timestamp"];
        }

        $htmlShortDifDir = "Http/Controllers/python/different/short_term/";
        $href_http = "http://" . $_SERVER['HTTP_HOST'];
        return view('show-customer')->with('customerPages', $customerPages)
                                    ->with('htmlPreDirs', $htmlPreDirs)
                                    ->with('htmlShortDifDir', $htmlShortDifDir)
                                    ->with('href_http', $href_http);
    }
}
