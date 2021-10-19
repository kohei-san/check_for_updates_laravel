<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    /*
    * PassRecordミドルウェアにて成績関係の変数は定義
    */
    public function index(Request $request){
        return view('analysis')->with('record', $request->record);
    }
}
