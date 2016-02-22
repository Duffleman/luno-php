<?php

use Duffleman\Luno\Exceptions\LunoApiException;
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

$faker = Factory::create();

$user = $luno->users->find('usr_HvgS9pBTmq3C7c');

try {
    $events = $luno->events->recent();

    dump($events);
} catch (LunoApiException $ex) {
    echo("\n------------------------------------\n");
    dd($ex->getLunoCode(), $ex->getLunoExtra());
}