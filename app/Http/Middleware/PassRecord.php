<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
// customer関係
use App\Models\Customer;
use App\Models\LongDifference;
// user関係
use App\Models\User;
use App\Models\ActiveCall;
use App\Models\Review;
use App\Models\LineRegister;

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
        $blogCustomersAll = Customer::where('blog_flg', 1)
                            ->where('active_flg', 1)
                            ->where('del_flg', 0)
                            ->count();
        $users = User::with(['line_register', 'active_call', 'review'])->get();
        $line = LineRegister::where('line_flg', 1)->count();
        $activeCall = ActiveCall::where('active_call_flg', 1)->count();
        $review = Review::where('review_flg', 1)->count();
        $haveLongDifference = LongDifference::selectRaw('count(customer_id) as customer_count, customer_id')
                            ->groupBy('customer_id')
                            ->where('difference_flg', 1)
                            ->get();
        $updated = $haveLongDifference->count();


        $record = [
            "blogCustomersAll" => $blogCustomersAll,
            "users" => $users,
            "line" => $line,
            "lineRegisterRate" => round($line / $blogCustomersAll * 100, 1),
            "activeCall" => $activeCall,
            "review" => $review,
            "updated" => $updated,
            // "sabunRate" => round(100 - ($updated / $blogCustomersAll * 100), 1),
        ];

        $request->merge(['record'=>$record]);

        return $next($request);
    }
}
