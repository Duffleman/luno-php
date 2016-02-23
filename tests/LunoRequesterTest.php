<?php

use Duffleman\Luno\LunoRequester;

class LunoRequesterTest extends PHPUnit_Framework_TestCase
{

    public function test_is_instantiable()
    {
        $luno = new LunoRequester();

        $this->assertInstanceOf('Duffleman\\Luno\\LunoRequester', $luno);
    }

    /**
     * @expectedException Duffleman\Luno\Exceptions\LunoLibraryException
     */
    public function test_cannot_send_requests_without_key_and_secret_set()
    {
        $luno = new LunoRequester();

        $users = $luno->users->recent();
    }

}