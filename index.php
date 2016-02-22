<?php

use Duffleman\Luno\Exceptions\LunoApiException;
use Duffleman\Luno\LunoRequester;
use Faker\Factory;
use GuzzleHttp\Client;

require_once(__DIR__ . '/vendor/autoload.php');

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$luno = new LunoRequester([
    'key'     => getenv('LUNO_KEY'),
    'secret'  => getenv('LUNO_SECRET'),
    'timeout' => 10000,
], new Client([
    'proxy'  => 'localhost:8888',
    'verify' => false,
]));

$faker = Factory::create();

$user = $luno->users->find('usr_HvgS9pBTmq3C7c');

try {
    $ip = $faker->ipv4;
    $agent = $faker->userAgent;

    $session = $luno->sessions->create([
        'ip'         => $ip,
        'user_agent' => $agent,
    ]);
} catch (LunoApiException $ex) {
    echo("\n------------------------------------\n");
    dd($ex->getLunoCode(), $ex->getLunoExtra());
}