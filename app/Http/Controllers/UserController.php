<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class UserController extends Controller
{
    /*
    * ビューに渡す変数はPassRecordミドルウェアにて定義
    */
    public function index(Request $request){
        return view('dashboard')->with('record', $request->record);
    }

    /**
     * ユーザー編集画面
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
