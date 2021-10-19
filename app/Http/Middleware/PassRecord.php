<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\LongDifference;
// user関係
use App\Models\User;
use App\Models\ActiveCall;
use App\Models\Review;
use App\Models\LineRegister;
use Kyslik\ColumnSortable\Sortable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PassRecord
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $allCustomers = Customer::where('blog_flg', 1)
                            ->where('active_flg', 1)
                            ->where('del_flg', 0)
                            ->count();
        $users = User::with(['line_register', 'active_call', 'review'])->get();
        $line = LineRegister::where('line_flg', 1)->count();
        $activeClall = ActiveCall::where('active_call_flg', 1)->count();
        $review = Review::where('review_flg', 1)->count();
        $haveLongDifference = LongDifference::selectRaw('count(customer_id) as customer_count, customer_id')
                            ->groupBy('customer_id')
                            ->where('difference_flg', 1)
                            ->get();
        $updated = $haveLongDifference->count();

        $record = [
            "allCustomers" => $allCustomers,
            "users" => $users,
            "line" => $line,
            "activeCall" => $activeClall,
            "review" => $review,
            "updated" => $updated,
        ];

        $request->merge(['record'=>$record]);

        return $next($request);
    }
}
