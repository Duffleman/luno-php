<?php

use Duffleman\Luno\Exceptions\LunoApiException;
use Duffleman\Luno\LunoRequester;

require_once(__DIR__ . '/vendor/autoload.php');

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$luno = new LunoRequester([
    'key' => getenv('LUNO_KEY'),
    'secret' => getenv('LUNO_SECRET'),
    'timeout' => 10000,
]);

try {
    $user = $luno->users->find('usr_jUR5KQWYHvYdKX');
} catch (LunoApiException $ex) {
    dd($ex->getLunoCode(), $ex->getLunoExtra());

}

dd($user);