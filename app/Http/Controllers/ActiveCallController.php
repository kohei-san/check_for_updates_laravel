<?php

namespace App\Http\Controllers;

use App\Models\ActiveCall;
use Illuminate\Http\Request;
use Illuminate\Notifications\Action;
use Illuminate\Support\Facades\Auth;

class ActiveCallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $json = json_decode($request->header('request'));
        // 未登録なら新規レコード作成
        if(ActiveCall::where('customer_id', $json->customer_id)->first() == null){
            $activeCall = new ActiveCall;

            $activeCall->active_call_flg = true;

            $activeCall->customer_id = $json->customer_id;
            $activeCall->user_id = Auth::id();
        }
        else{ // 登録済みのユーザーのステータス変更
            $activeCall = ActiveCall::where('customer_id', $json->customer_id)->first();
            $active_call_flg = boolval($activeCall->active_call_flg);
            if($active_call_flg == $json->registered){
                $activeCall->active_call_flg = !$active_call_flg;
                // 誤って登録を消したのが、登録したユーザーと同じユーザーなら、user_idを削除(誤操作、不正クリック予防)
                if(($activeCall->active_call_flg == false) && ($activeCall->user_id == Auth::id())){
                    $activeCall->user_id = null;
                } //登録時に、以前登録したユーザーがいなければ登録
                elseif(($activeCall->active_call_flg == true) && ($activeCall->user_id == null)){
                    $activeCall->user_id = Auth::id();
                }
            }
        }
        $activeCall->save();
        
        return $activeCall;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ActiveCall  $activeCall
     * @return \Illuminate\Http\Response
     */
    public function show(ActiveCall $activeCall)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ActiveCall  $activeCall
     * @return \Illuminate\Http\Response
     */
    public function edit(ActiveCall $activeCall)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ActiveCall  $activeCall
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ActiveCall $activeCall)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ActiveCall  $activeCall
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActiveCall $activeCall)
    {
        //
    }
}
