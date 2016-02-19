<?php

use Duffleman\Luno\LunoRequester;

require_once(__DIR__ . '/../vendor/autoload.php');

$luno = new LunoRequester([
    'key'     => '<YOUR_LUNO_KEY>',
    'secret'  => '<YOUR_LUNO_SECRET>',
    'timeout' => 10000
]);

// Cycle through the top 100 users.
$user_analytics = $luno->analytics->users();

echo("{$user_analytics['total']} users have signed up.");

$data = $luno->analytics->timeline([
    'distinct' => false,
    'group'    => 'week'
]);

dd($data);