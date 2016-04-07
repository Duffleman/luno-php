<?php

use Duffleman\Luno\LunoRequester;

class AccountInteractorTest extends TestCase
{

    /**
     * Setup method used for all test cases.
     *
     * We override it here because this is the only place we do NOT want to Sandbox.
     */
    public static function setUpBeforeClass()
    {
        $dotenv = new Dotenv\Dotenv(__DIR__ . '/..');
        $dotenv->load();

        self::$luno = new LunoRequester([
            'key'     => getenv('LUNO_KEY'),
            'secret'  => getenv('LUNO_SECRET'),
            'timeout' => 10000,
            'sandbox' => false,
        ]);
    }

    public function test_account_details_can_be_retrieved()
    {
        $data = self::$luno->account->get();

        $this->assertTrue(is_array($data));
        $this->assertArrayHasKey('type', $data);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('name', $data);
    }
}