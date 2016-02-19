<?php

use Duffleman\Luno\LunoRequester;

require_once(__DIR__ . '/../vendor/autoload.php');

$luno = new LunoRequester([
    'key'     => '<YOUR_LUNO_KEY>',
    'secret'  => '<YOUR_LUNO_SECRET>',
    'timeout' => 10000
]);

// Get the users analytics data.
$user_analytics = $luno->analytics->users();
echo("{$user_analytics['total']} users have signed up.");

// Grab timeline data for events.
$data = $luno->analytics->timeline([
    'distinct' => false,
    'group'    => 'week'
]);

dd($data);