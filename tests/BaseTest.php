<?php


abstract class BaseTestClass extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $dotenv = new Dotenv\Dotenv(__DIR__);
        $dotenv->load();
    }

}