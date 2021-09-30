<?php

namespace App\Http\Controllers;

use App\Models\LineRegister;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;


class LineRegisterController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LineRegister  $lineRegister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LineRegister $lineRegister)
    {
        $headers = $request->headers;
        foreach( $headers as $key=>$value){
            if($key == 'request'){
                $json = json_decode($value[0], true);
                break;
            }
        }
        // 未登録なら新規レコード作成
        if(LineRegister::find($json['support_id']) == null){
            $lineRecord = new LineRegister;

            $lineRecord->line_flg = true;
            $lineRecord->support_id = $json['support_id'];
            $lineRecord->user_id = Auth::id();
            
            $lineRecord->save();
        }
        // 登録済みのユーザーのステータス変更
        else{
            $lineRecord = LineRegister::find($json['support_id']);
            $line_flg = boolval($lineRecord->line_flg);
    
            // LINEステータス登録済みの顧客の場合
            if($line_flg == $json['registered'] && $lineRecord->support_id == $json['support_id']){
                $lineRecord->line_flg = !$line_flg;
                $lineRecord->save();
            }
        }
            
        return $lineRecord;
    }

    // LineRegisterテーブルにcustomer_id追加
    public function writeCustomerId(){
        $customers = Customer::all();
        $lineRecords = LineRegister::all();

        foreach($lineRecords as $lineRecord){
            if($lineRecord->customer_id != null){
                continue;
            }
            foreach($customers as $customer){
                if($lineRecord->support_id == $customer->support_id){
                    $lineRecord->customer_id = $customer->customer_id;
                    $lineRecord->save();
                    break;
                }
            }
        }
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LineRegister  $lineRegister
     * @return \Illuminate\Http\Response
     */
    public function destroy(LineRegister $lineRegister)
    {
        //
    }
}
