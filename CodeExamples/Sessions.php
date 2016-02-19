<?php

use Duffleman\Luno\LunoRequester;

require_once(__DIR__ . '/../vendor/autoload.php');

$luno = new LunoRequester([
    'key'     => '<YOUR_LUNO_KEY>',
    'secret'  => '<YOUR_LUNO_SECRET>',
    'timeout' => 10000
]);

// Create a Session
$session = $luno->sessions->create([
    'id'         => 'USER_ID',
    'expand'     => 'user',
    'ip'         => '10.0.0.01',
    'user_agent' => 'Users_user_agent',
    'details'    => [
        'key' => 'value',
        'no'  => 'arrays',
    ]
]);

// Don't forget, this is the session key, not the session ID.
// Then an array of attributes for the session.
// Then TRUE or FALSE if you want the entire user object returned too.
$session = $luno->sessions->access('d7e7272012f524092d42f6e21e13133556978e4872ee86657a2248593d435931',
    ['ip' => '195.171.185.51'], true);

// Get the latest 100 sessions.
$sessions = $luno->sessions->recent();

// Get the latest 100 sessions from this single user.
$sessions = $luno->sessions->where(['user.id' => '<USER_STRING>'])->recent();