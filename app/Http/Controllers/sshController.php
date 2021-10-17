<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class sshController extends Controller
{
    public function makeconnection()
    {
        return new Filesystem(new SftpAdapter([
          'host'        => $config['host'],
          'port'        => $config['port'],
          'username'    => $config['username'],
          'password'    => $config['password'],
          'privateKey'  => $config['privateKey'],
          'root'        => $config['root'],
          'timeout'     => $config['timeout'],
        ]));
    }

    public function getfile(){

        makeconnection()

        // $contents = file_get_contents(storage_path('test.txt'));
        // Storage::disk('sftp')->put('file.txt', $contents);
    }
}
