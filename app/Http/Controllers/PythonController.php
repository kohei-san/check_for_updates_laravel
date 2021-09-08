<?php

namespace App\Http\Controllers;
use App\Providers\RouteServiceProvider;

use Illuminate\Http\Request;

class PythonController extends Controller
{
    public function exec() {
        $dir = __DIR__;
        $py_file = $dir.'/python/test.py';

        exec($py_file, $output);

        // dd($output);

        // return redirect()->intended(RouteServiceProvider::HOME)
        //     ->with(['message' => $py_file,
        //     'status' => 'info']);

        return redirect()->intended(RouteServiceProvider::HOME)
        ->with(['message' => $output[0],
        'status' => 'info']);
    }
}
