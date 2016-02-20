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

// Find a specific user.
$user = $luno->users->find('<USER_ID>');

// Append a key value pair to the users profile.
$luno->users->append($user_id, [
    'profile' => [
        'attribute' => 'value'
    ]
]);

// Cycle through the latest 100 users.
$users = $luno->users->recent();
foreach ($users as $user) {
    echo($user->name . "\n");
}