<?php

$host = 'http://f-book.easys.jp/';
$ssh = ssh2_connect($host);
if (false === $ssh) {
  die('connection failed');
}
?>
