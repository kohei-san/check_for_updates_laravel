<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
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
        $review = new Review;

        $review->review_flg = true;
        $review->customer_id = $customer_id;
        $review->user_id = Auth::id();

        return $review;
    }
    /**
     * 関数内容: 口コミ登録
     * 条件: 
     * 1: 未登録なら新規レコード作成
     * 2: 既存レコードに同じcustomer_idがあり、updated_atが3か月以上前ならば、新しいレコード作成
     * 3: 既存レコードに同じcustomer_idがあり、updated_atが3か月以内ならば、同レコードを編集
     */
    public function store(Request $request)
    {
        $json = json_decode($request->header('request'));
        // 1
        if(Review::where('customer_id', $json->customer_id)->first() == null){
            $review = $this->new($json->customer_id);
        }
        else{
            $review = Review::where('customer_id', $json->customer_id)->orderByDesc('updated_at')->first();
            $a_day_after_updated = Carbon::parse($review->updated_at)->addMonth(3);
            // 2
            if( Carbon::now()->greaterThan($a_day_after_updated)){
                $review = $this->new($json->customer_id);
            }
            elseif($review->review_flg == true && $review->user_id == Auth::id()){
                $review->review_flg = false;
                $review->delete();

                return $review;
            }
            else{ // 3
                $review_flg = boolval($review->review_flg);
                if($review_flg == $json->registered){
                    $review->review_flg = !$review_flg;
                    /**
                     * 不正登録防止
                     * 誤って登録を消したのが、登録したユーザーと同じユーザーなら、user_idを削除(誤操作、不正クリック予防)
                     * 登録時に、以前登録したユーザーがいなければ登録
                     */
                    if(($review->review_flg == false) && ($review->user_id == Auth::id())){
                        $review->user_id = null;
                    }
                    elseif(($review->review_flg == true) && ($review->user_id == null)){
                        $review->user_id = Auth::id();
                    }
                }
            }
        }
        $review->save();
        
        return $review;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        //
    }
}
