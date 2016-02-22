<?php

use Duffleman\Luno\LunoRequester;

class AnalyticsInteractorTest extends PHPUnit_Framework_TestCase
{

    protected static $luno;

    public function setUp()
    {
        $dotenv = new Dotenv\Dotenv(__DIR__ . '/..');
        $dotenv->load();

        self::$luno = new LunoRequester([
            'key'     => getenv('LUNO_KEY'),
            'secret'  => getenv('LUNO_SECRET'),
            'timeout' => 10000
        ]);
    }

    public function test_user_analytics()
    {
        $analytics = self::$luno->analytics->users(['total', 7, 27]);

        $this->assertArrayHasKey('total', $analytics);
        $this->assertArrayHasKey('7_days', $analytics);
        $this->assertArrayHasKey('27_days', $analytics);
    }

    public function test_session_analytics()
    {
        $analytics = self::$luno->analytics->sessions(['total', 7, 27]);

        $this->assertArrayHasKey('total', $analytics);
        $this->assertArrayHasKey('7_days', $analytics);
        $this->assertArrayHasKey('27_days', $analytics);
    }

    public function test_event_analytics()
    {
        $analytics = self::$luno->analytics->events(['total', 7, 27]);

        $this->assertArrayHasKey('total', $analytics);
        $this->assertArrayHasKey('7_days', $analytics);
        $this->assertArrayHasKey('27_days', $analytics);
    }

    public function test_analytics_events_list()
    {
        $events = self::$luno->analytics->listEvents();

        $this->assertTrue(is_array($events));

        foreach ($events['list'] as $event) {
            $this->assertArrayHasKey('name', $event);
            $this->assertArrayHasKey('count', $event);
            $this->assertArrayHasKey('last', $event);
            $this->assertArrayHasKey('url', $event);
        }
    }
}