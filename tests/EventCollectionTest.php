<?php

use Duffleman\Luno\LunoRequester;

class EventCollectionTest extends PHPUnit_Framework_TestCase
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

    public function test_can_list_recent_100_events()
    {
        $events = self::$luno->events->recent();

        $this->assertLessThanOrEqual(100, count($events));
    }

    public function test_an_event_can_be_created()
    {
        $name = self::$faker->sentence;

        $event = self::$luno->events->create([
            'user_id' => self::$user['id'],
            'name'    => $name,
            'details' => [
                'field' => 'attribute',
            ]
        ]);

        $this->assertTrue($event['type'] === 'event', "Event type is not accurate.");
        $this->assertTrue($event['name'] === $name, "Event name is wrong.");
        $this->assertTrue($event['details'] === ['field' => 'attribute'], "Details do not match.");
        $this->assertNotNull($event['user'], "A user is not attached.");

        return $event;
    }

    public function test_events_can_be_retrieved_by_user_id()
    {
        $user = self::$luno->users->create([
            'username' => self::$faker->userName,
            'name'     => self::$faker->name,
            'password' => self::$faker->password,
            'email'    => self::$faker->email,
        ]);

        $build = 5;

        for ($i = 0; $i < $build; $i++) {
            self::$luno->events->create([
                'name'    => self::$faker->sentence,
                'user_id' => $user['id'],
            ]);
        }

        $events = self::$luno->events->recent([
            'limit'   => 5,
            'user_id' => $user['id']
        ]);

        $this->assertCount(5, $events);

        foreach ($events as $iterator_event) {
            $this->assertTrue($iterator_event['user']['id'] === $user['id']);
        }

        self::$luno->users->destroy($user['id']);
    }

    /**
     * @depends test_an_event_can_be_created
     * @param array $event
     * @return array
     */
    public function test_an_event_can_be_retrieved_by_id(array $event)
    {
        $downloaded_event = self::$luno->events->find($event['id']);

        $this->assertTrue($downloaded_event['id'] === $event['id']);
        $this->assertTrue($downloaded_event['timestamp'] === $event['timestamp']);
        $this->assertTrue($downloaded_event['name'] === $event['name']);

        return $downloaded_event;
    }

    /**
     * @depends test_an_event_can_be_retrieved_by_id
     * @param array $event
     * @return array
     */
    public function test_an_event_can_be_updated(array $event)
    {
        $new_body = [
            'details' => [
                'updated_field' => 'updated_value',
            ],
        ];

        $expected_details = [
            'field'         => 'attribute',
            'updated_field' => 'updated_value',
        ];

        $response = self::$luno->events->append($event['id'], $new_body);
        $fresh_event = self::$luno->events->find($event['id']);

        $this->assertTrue($response, "Could not append the new body.");
        $this->assertTrue($fresh_event['details'] === $expected_details, "The details have not been updated.");

        return $fresh_event;
    }

    /**
     * @depends test_an_event_can_be_updated
     * @param array $event
     * @return array
     */
    public function test_an_event_can_be_overwritten(array $event)
    {
        $new_body = [
            'details' => [
                'final' => '_value'
            ],
        ];

        $expected_details = [
            'final' => '_value',
        ];

        $response = self::$luno->events->overwrite($event['id'], $new_body);
        $fresh_session = self::$luno->events->find($event['id']);

        $this->assertTrue($response, "Could not append the new body.");
        $this->assertTrue($fresh_session['details'] === $expected_details, "The details have not been updated.");

        return $fresh_session;
    }

    /**
     * @depends test_an_event_can_be_overwritten
     * @param array $event
     */
    public function test_an_event_can_be_deleted(array $event)
    {
        $response = self::$luno->events->destroy($event['id']);

        $this->assertTrue($response);
    }
}