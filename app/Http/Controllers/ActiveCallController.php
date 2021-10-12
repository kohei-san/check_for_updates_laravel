<?php

namespace App\Http\Controllers;

use App\Models\ActiveCall;
use Illuminate\Http\Request;
use Illuminate\Notifications\Action;
use Illuminate\Support\Facades\Auth;
use \Carbon\Carbon;

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
     * 関数名: new()
     * DBに新しいレコードを作成する場合に呼び出す。
     */
    protected function new($customer_id)
    {
        $activeCall = new ActiveCall;

        $activeCall->active_call_flg = true;
        $activeCall->customer_id = $customer_id;
        $activeCall->user_id = Auth::id();

        return $activeCall;
    }

    /**
     * 関数内容: アクティブコール登録
     * 条件: 
     * 1: 未登録なら新規レコード作成
     * 2: 既存レコードに同じcustomer_idがあり、updated_atが1日以上前ならば、新しいレコード作成
     * 3: 既存レコードに同じcustomer_idがあり、updated_atが1日以内ならば、同レコードを編集
     */
    public function store(Request $request)
    {
        $json = json_decode($request->header('request'));
        // 1
        if(ActiveCall::where('customer_id', $json->customer_id)->first() == null){
            $activeCall = $this->new($json->customer_id);
        }
        else{
            $activeCall = ActiveCall::where('customer_id', $json->customer_id)->orderByDesc('updated_at')->first();
            $a_day_after_updated = Carbon::parse($activeCall->updated_at)->addDay(1);
            // 2
            if( Carbon::now()->greaterThan($a_day_after_updated)){
                $activeCall = $this->new($json->customer_id);
            }
            else{ // 3
                $active_call_flg = boolval($activeCall->active_call_flg);
                if($active_call_flg == $json->registered){
                    $activeCall->active_call_flg = !$active_call_flg;
                    /**
                     * 不正登録防止
                     * 誤って登録を消したのが、登録したユーザーと同じユーザーなら、user_idを削除(誤操作、不正クリック予防)
                     * 登録時に、以前登録したユーザーがいなければ登録
                     */
                    if(($activeCall->active_call_flg == false) && ($activeCall->user_id == Auth::id())){
                        $activeCall->user_id = null;
                    }
                    elseif(($activeCall->active_call_flg == true) && ($activeCall->user_id == null)){
                        $activeCall->user_id = Auth::id();
                    }
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
