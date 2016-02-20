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

$user = $luno->users->create([
    'username' => $faker->userName,
    'name'     => $faker->name,
    'email'    => $faker->email,
    'password' => $faker->password,
]);

try {
    $luno->users->changePassword($user['id'], 'myPassword');
} catch(LunoApiException $ex)
{
    dump($ex->getLunoCode(), $ex->getLunoExtra());
}
dump($user);

$luno->users->destroy($user['id']);