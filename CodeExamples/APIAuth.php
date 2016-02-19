<?php

use Duffleman\Luno\LunoRequester;

require_once(__DIR__ . '/../vendor/autoload.php');

$luno = new LunoRequester([
    'key'     => '<YOUR_LUNO_KEY>',
    'secret'  => '<YOUR_LUNO_SECRET>',
    'timeout' => 10000
]);

// Create an API key
$api_key = $luno->api->create([
    'id'      => 'USER_ID',
    'expand'  => 'user',
    'details' => [
        'key' => 'value',
        'no'  => 'arrays',
    ]
]);

// You cannot do this. This does not work.
$keys = $luno->api->recent(); // BAD BAD BAD.

// Get the latest 100 sessions from this single user.
// This DOES work.
$keys = $luno->api->where(['user.id' => '<USER_STRING>'])->recent();

// Find an API key by it's key.
$key_data = $luno->api->find('<APIKEY_KEY>');