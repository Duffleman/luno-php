<?php

use Duffleman\Luno\LunoRequester;

require_once(__DIR__ . '/../vendor/autoload.php');

$luno = new LunoRequester([
    'key'     => '<YOUR_LUNO_KEY>',
    'secret'  => '<YOUR_LUNO_SECRET>',
    'timeout' => 10000
]);

// Cycle through the top 100 users.
$users = $luno->users->recent();
foreach ($users as $user) {
    echo($user->name . "\n");
}

// Find the user from an ID.
$user = $this->users->find('usr_string');
$user_id = $user['id'];

// Append a key => value pair to the profile of a user.
$luno->users->append($user_id, [
    'profile' => [
        'attribute' => 'value'
    ]
]);