<?php

use Duffleman\Luno\Exceptions\LunoApiException;
use Duffleman\Luno\LunoRequester;

require_once(__DIR__ . '/../vendor/autoload.php');

$luno = new LunoRequester([
    'key'     => '<YOUR_LUNO_KEY>',
    'secret'  => '<YOUR_LUNO_SECRET>',
    'timeout' => 10000
]);

// Create a user
try {
    $user = $luno->users->create([
        'username' => 'Duffleman',
        'name'     => 'James Duffleman',
        'email'    => 'james@duffleman.co.uk',
        'password' => 'myPassword',
        'profile'  => [
            'attribute' => 'value'
        ]
    ]);
} catch (LunoApiException $exception) {
    // Log Something
    // Do something
}
