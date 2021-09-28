<?php

namespace App\Http\Controllers;
use App\Providers\RouteServiceProvider;

use Illuminate\Http\Request;

class PythonController extends Controller
{
    public function exec() {
        // 本番環境のpythonディレクトリ
        $env = config('app.env');
        if($env == 'production'){
            $dir = __DIR__;
            $python_dir = '/home/xs330114/anaconda3/bin/python3.8';
            $py_file = $python_dir.' '.$dir.'/test_server/server_test.py 2>&1';

            exec($py_file, $output, $result);

            return redirect()->intended(RouteServiceProvider::HOME)
            ->with(['message' => $output[0],
            'status' => 'info']);
        }

        // 開発環境のpythonディレクトリ
        $dir = __DIR__;
        $python_dir = 'C:\Users\miyatake\anaconda3\python.exe';
        // $py_file = $python_dir.' '.$dir.'\test_server\server_test.py 2>&1';
        $py_file = 'python'.' '.$dir.'\test_server\server_test.py 2>&1';
        exec($py_file, $output, $result);

        return redirect()->intended(RouteServiceProvider::HOME)
        ->with(['message' => $output[0],
        'status' => 'info']);
    }
}
