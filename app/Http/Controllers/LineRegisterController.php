<?php

namespace App\Http\Controllers;

use App\Models\LineRegister;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

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
        if(LineRegister::find($json['customer_id']) == null){
            $lineRecord = new LineRegister;

            $lineRecord->line_flg = true;
            $lineRecord->customer_id = $json['customer_id'];
            $lineRecord->user_id = Auth::id();
            
            $lineRecord->save();
        }
        // 登録済みのユーザーのステータス変更
        else{
            $lineRecord = LineRegister::find($json['customer_id']);
            $line_flg = boolval($lineRecord->line_flg);
    
            // LINEステータス登録済みの顧客の場合
            if($line_flg == $json['registered'] && $lineRecord->customer_id == $json['customer_id']){
                $lineRecord->line_flg = !$line_flg;
                $lineRecord->save();
            }
        }
            
        return $lineRecord;
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
