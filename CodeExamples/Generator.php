<?php

use Duffleman\Luno\LunoRequester;

require_once(__DIR__ . '/../vendor/autoload.php');

$luno = new LunoRequester([
    'key'     => '<YOUR_LUNO_KEY>',
    'secret'  => '<YOUR_LUNO_SECRET>',
    'timeout' => 10000
]);

$users = $luno->users->all();
// dd($users); // Returns the Generator

$counter = 0;
foreach ($users as $user) {

    if (array_key_exists('field_3', $user['profile'])) {
        echo("Found 'field_3' in {$user['name']}'s profile!\n");
        echo("It is set to '{$user['profile']['field_3']}'.\n\n");
    }

    $counter++;
}

/*
 * Seems odd we can loop through every single user to search through their profile...
 * If only we could search through it with some sort of API..... *cough*
 */