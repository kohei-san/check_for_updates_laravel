<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Models\Customer;
use App\Models\CustomerPage;
use App\Models\LineRegister;
use App\Models\LongDifference;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    
    public function index(){
        // ライン登録ユーザーの割合
        $lineusers = LineRegister::where('line_flg', 1)->count();
        $customers = Customer::count();
        $linedata = array(
            'rate' => round($lineusers / $customers * 100, 1),
            'all' => $lineusers,
        );

        // ブログ更新ユーザーの割合
        $haveLongDiff = DB::table('difference_bet_longterm')
                            ->select(DB::raw('count(*) as customer_count, customer_id'))
                            ->groupBy('customer_id')
                            ->where('difference_flg', 1)
                            ->get();
        $haveLongDiff = $haveLongDiff->count();

        $blogUser = Customer::where('blog_flg', 1)->count();
        $allCustomer = Customer::count();
        $sabun = array(
            'rate' => round(100 - ($haveLongDiff / $allCustomer * 100), 1),
            'all' => $haveLongDiff,
            'blog' => $blogUser,
        );

        return view('dashboard')->with('linedata' ,$linedata)
                            ->with('sabun', $sabun)
                            ->with('customers',$customers);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('edit-user', Auth::user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user = Auth::user();

        $user->name = $request->name;
        $user->is_admin = $request->is_admin;

        $user->save();

        return redirect()->intended(RouteServiceProvider::HOME)
            ->with(['message' => 'ユーザー情報を更新しました。',
            'status' => 'alert']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
