<?php

use Duffleman\Luno\LunoRequester;

class ApiCollectionTest extends PHPUnit_Framework_TestCase
{

    protected static $luno;
    protected static $faker;
    protected static $user;

    public function setUp()
    {
        $dotenv = new Dotenv\Dotenv(__DIR__ . '/..');
        $dotenv->load();

        self::$faker = Faker\Factory::create();

        self::$luno = new LunoRequester([
            'key'     => getenv('LUNO_KEY'),
            'secret'  => getenv('LUNO_SECRET'),
            'timeout' => 10000
        ]);

        self::$user = self::$luno->users->create([
            'username' => self::$faker->userName,
            'name'     => self::$faker->name,
            'email'    => self::$faker->email,
            'password' => self::$faker->password,
        ]);
    }
    
    public static function tearDownAfterClass()
    {
        self::$luno->users->destroy(self::$user['id']);
    }

    public function test_api_keys_can_be_retrieved()
    {
        $keys = self::$luno->apikeys->recent();

        $this->assertLessThanOrEqual(100, count($keys));
    }

    /**
     * @return array
     */
    public function test_an_api_key_can_be_created()
    {
        $key = self::$luno->apikeys->create([
            'user_id' => self::$user['id'],
            'details' => [
                'field' => 'value',
            ],
        ]);

        $this->assertTrue(is_array($key));
        $this->assertArrayHasKey('type', $key);
        $this->assertArrayHasKey('key', $key);
        $this->assertArrayHasKey('secret', $key);
        $this->assertTrue($key['type'] === 'api_authentication');

        return $key;
    }

    /**
     * @depends test_an_api_key_can_be_created
     * @param array $key
     * @return array
     */
    public function test_a_key_can_be_retrieved_by_its_key(array $key)
    {
        $fresh_key = self::$luno->apikeys->find($key['key']);

        $this->assertTrue($fresh_key['type'] === 'api_authentication');
        $this->assertArrayHasKey('user', $fresh_key);

        return $key;
    }

    /**
     * @depends test_a_key_can_be_retrieved_by_its_key
     * @param array $key
     * @return array
     */
    public function test_an_api_key_can_be_updated(array $key)
    {
        $new_details = [
            'details' => [
                '_field' => '_value',
            ],
        ];

        $expected = [
            'field'  => 'value',
            '_field' => '_value',
        ];

        self::$luno->apikeys->append($key['key'], $new_details);
        $fresh_key = self::$luno->apikeys->find($key['key']);

        $this->assertTrue($fresh_key['details'] === $expected);

        return $fresh_key;
    }

    /**
     * @depends test_an_api_key_can_be_updated
     * @param array $key
     * @return array
     */
    public function test_an_api_key_can_be_overwritten(array $key)
    {
        $new_details = [
            'details' => [
                'updated_field' => 'updated_value',
            ],
        ];

        $expected = [
            'updated_field' => 'updated_value',
        ];

        self::$luno->apikeys->overwrite($key['key'], $new_details);
        $fresh_key = self::$luno->apikeys->find($key['key']);

        $this->assertTrue($fresh_key['details'] === $expected);

        return $fresh_key;
    }

    public function test_a_set_of_api_keys_can_be_retrieved_by_user()
    {
        $build = 5;

        for ($i = 0; $i < $build; $i++) {
            self::$luno->apikeys->create([
                'user_id' => self::$user['id'],
            ]);
        }

        $keys = self::$luno->apikeys->recent([
            'user_id' => self::$user['id'],
        ]);

        $this->assertCount($build, $keys);

        foreach ($keys as $key) {
            $this->assertTrue($key['user']['id'] === self::$user['id']);
        }
    }

    /**
     * @depends test_an_api_key_can_be_overwritten
     * @param array $key
     */
    public function test_an_api_key_can_be_destroyed(array $key)
    {
        $response = self::$luno->apikeys->destroy($key['key']);

        $this->assertTrue($response);
    }
}