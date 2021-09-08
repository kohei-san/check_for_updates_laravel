<?php

namespace App\Http\Controllers;
use App\Providers\RouteServiceProvider;

use Illuminate\Http\Request;

class PythonController extends Controller
{
    public function exec() {

        

        return redirect()->intended(RouteServiceProvider::HOME)
        ->with(['message' => 'pythonファイルを実行しました。',
        'status' => 'info']);
    }
}
