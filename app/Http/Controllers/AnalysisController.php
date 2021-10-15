<?php

namespace App\Http\Controllers;

use App\Models\ActiveCall;
use Illuminate\Http\Request;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Customer;
use App\Models\LineRegister;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use App\Models\LongDifference;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index(){
        $users = User::with(['line_register', 'active_call', 'review'])
                    ->get();
        
        $lineCount = LineRegister::where('line_flg', 1)->count();

        $activeClallCount = ActiveCall::where('active_call_flg', 1)->count();

        $reviewCount = Review::where('review_flg', 1)->count();

        $updated = LongDifference::selectRaw('count(customer_id) as customer_count, customer_id')
                                ->groupBy('customer_id')
                                ->where('difference_flg', 1)
                                ->get();
        $updated = $updated->count();

        $allCustomers = Customer::where('blog_flg', 1)
                                ->where('active_flg', 1)
                                ->where('del_flg', 0)
                                ->count();

        return view('analysis')->with('users', $users)
                                ->with('lineCount', $lineCount)
                                ->with('activeCallCount', $activeClallCount)
                                ->with('reviewCount', $reviewCount)
                                ->with('allCustomers', $allCustomers)
                                ->with('updated', $updated);
    }
}
