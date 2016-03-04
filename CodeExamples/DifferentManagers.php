<?php

use Duffleman\Luno\LunoRequester;
use Duffleman\Luno\Managers\GenericClassManager;

require_once(__DIR__ . '/vendor/autoload.php');

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$luno = new LunoRequester([
    'key'     => getenv('LUNO_KEY'),
    'secret'  => getenv('LUNO_SECRET'),
    'timeout' => 10000,
], null, new GenericClassManager());

$users = $luno->users->recent(['limit' => 5]);

foreach ($users as $user) {
    // Notice this is not an array anymore!
    // Use `->` instead :)
    echo("Found {$user->name}.\n");
}

echo("-------------------\n");

$sessions = $luno->sessions->recent(['limit' => 5]);

foreach ($sessions as $session) {
    // Notice this is not an array anymore!
    // Use `->` instead :)
    echo("Found {$session->key}.\n");
}