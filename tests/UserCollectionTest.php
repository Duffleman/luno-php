<?php


use Duffleman\Luno\LunoRequester;

class UserCollectionTest extends BaseTestClass
{

    protected $luno;

    public function setUp()
    {
        $this->luno = new LunoRequester([
            'key' => env('LUNO_KEY'),
            'secret' => env('LUNO_SECERT'),
            'timeout' => 10000
        ]);
    }

    public function test_user_can_be_created()
    {
        $user_details = [
            'username' => 'Test User',
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => str_random(16),
        ];

        $this->luno->users->create($user_details);
    }


}