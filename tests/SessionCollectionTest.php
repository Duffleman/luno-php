<?php

use Duffleman\Luno\LunoRequester;
use GuzzleHttp\Client;

class SessionCollectionTest extends PHPUnit_Framework_TestCase
{

    /**
     * LunoRequester instance.
     *
     * @var LunoRequester
     */
    protected static $luno;

    /**
     * Faker instance.
     *
     * @var
     */
    protected static $faker;

    /**
     * User array to use for sessions.
     *
     * @var array
     */
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
        ], new Client([
            'proxy'  => 'localhost:8888',
            'verify' => false,
        ]));

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

    public function test_can_list_recent_100_sessions()
    {
        $sessions = self::$luno->sessions->recent();

        $this->assertLessThanOrEqual(100, count($sessions));
    }

    public function test_an_annon_session_can_be_created()
    {
        $ip = self::$faker->ipv4;
        $agent = self::$faker->userAgent;

        $session = self::$luno->sessions->create([
            'ip'         => $ip,
            'user_agent' => $agent,
        ]);

        $this->assertTrue($session['type'] === 'session', "Session type is not accurate.");
        $this->assertTrue($session['ip'] === $ip, "IP Address does not match.");
        $this->assertTrue($session['user_agent'] === $agent, "User Agent does not match.");
        $this->assertNull($session['user'], "A user is attached.");

        return $session;
    }

    public function test_a_session_can_be_built_for_a_user()
    {
        $user_id = self::$user['id'];
        $ip = self::$faker->ipv4;

        $session = self::$luno->sessions->create([
            'ip'      => $ip,
            'user_id' => $user_id,
            'details' => [
                'example' => 'attribute',
            ]
        ], 'user');

        $this->assertTrue($session['type'] === 'session', "Session type is not accurate.");
        $this->assertTrue($session['ip'] === $ip, "IP Address does not match.");
        $this->assertNotNull($session['user'], "A user is not attached.");
        $this->assertTrue($session['user']['id'] === $user_id, "User ID is wrong for the generated session.");

        return $session;
    }

    /**
     * @depends test_a_session_can_be_built_for_a_user
     * @param array $session
     */
    public function test_a_session_can_be_found_by_id(array $session)
    {
        $downloaded_session = self::$luno->sessions->find($session['id']);

        $this->assertTrue($downloaded_session['id'] === $session['id']);
        $this->assertTrue($downloaded_session['key'] === $session['key']);
        $this->assertTrue($downloaded_session['created'] === $session['created']);

        return $downloaded_session;
    }

    /**
     * @depends test_a_session_can_be_found_by_id
     * @param array $session
     */
    public function test_a_session_can_be_updated_keeping_old_details(array $session)
    {
        $new_ip = self::$faker->ipv6;

        $new_body = [
            'ip'      => $new_ip,
            'details' => [
                'field' => 'value'
            ],
        ];

        $expected_details = [
            'field'   => 'value',
            'example' => 'attribute',
        ];

        $response = self::$luno->sessions->append($session['id'], $new_body);
        $fresh_session = self::$luno->sessions->find($session['id']);

        $this->assertTrue($response, "Could not append the new body.");
        $this->assertTrue($fresh_session['ip'] === $new_ip, "IP does not match the new IP.");
        $this->assertTrue($fresh_session['details'] === $expected_details, "The details have not been updated.");

        return $fresh_session;
    }
}