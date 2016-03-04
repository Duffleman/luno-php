<?php

use Duffleman\Luno\LunoRequester;
use Faker\Factory;

abstract class TestCase extends PHPUnit_Framework_TestCase
{

    /**
     * @var LunoRequester
     */
    protected static $luno;

    /**
     * @var Factory
     */
    protected static $faker;

    /**
     * Setup method used for all test cases.
     */
    public static function setUpBeforeClass()
    {
        $dotenv = new Dotenv\Dotenv(__DIR__ . '/..');
        $dotenv->load();

        self::$faker = Faker\Factory::create();

        self::$luno = new LunoRequester([
            'key'     => getenv('LUNO_KEY'),
            'secret'  => getenv('LUNO_SECRET'),
            'timeout' => 10000
        ]);
    }
}