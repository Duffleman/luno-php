<?php

use Duffleman\Luno\LunoRequester;

class LunoRequesterTest extends PHPUnit_Framework_TestCase
{

    public function test_is_instantiable()
    {
        $luno = new LunoRequester();

        $this->assertInstanceOf('Duffleman\\Luno\\LunoRequester', $luno);
    }

}