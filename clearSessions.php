<?php

use Duffleman\Luno\LunoRequester;
use Faker\Factory;

require_once(__DIR__ . '/vendor/autoload.php');

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$luno = new LunoRequester([
    'key'     => getenv('LUNO_KEY'),
    'secret'  => getenv('LUNO_SECRET'),
    'timeout' => 10000,
]);

/**
 * Screw around here :P
 */

$sessions = $luno->sessions->all();

foreach ($sessions as $session) {
    $luno->sessions->destroy($session['id']);
}