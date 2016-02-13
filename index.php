<?php

use Duffleman\Luno\LunoRequester;
use GuzzleHttp\Client;

require_once(__DIR__ . '/vendor/autoload.php');

$luno = new LunoRequester([
    'key'     => 'YOUR-KEY',
    'secret'  => 'YOUR-SECRET',
    'timeout' => 10000
]);

echo $luno->request("GET", "/users");
