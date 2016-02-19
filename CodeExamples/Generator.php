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
// $users has to be looped through.

$counter = 0;
foreach ($users as $user) {

    echo($user['name'] . ": \n");

    if (array_key_exists('field_3', $user['profile'])) {
        echo("Found 'field_3' in {$user['name']}'s profile!\n");
        echo("It is set to '{$user['profile']['field_3']}'.\n\n");
        echo("------------\n");
    }


    $counter++;
}