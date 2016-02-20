<?php

use Duffleman\Luno\LunoRequester;

class LunoRequesterTest extends BaseTestClass
{

    public function test_is_instantiable()
    {
        $luno = new LunoRequester();

        $this->assertInstanceOf('Duffleman\\Luno\\LunoRequester', $luno);
    }

}