<?php

class SessionCollectionTest extends TestCase
{

    use UsesAFakeUser;

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
     * @return array
     */
    public function test_a_session_be_retrieved_for_a_user_using_generators()
    {
        $user = self::$luno->users->create([
            'username' => self::$faker->userName,
            'name'     => self::$faker->name,
            'password' => self::$faker->password,
            'email'    => self::$faker->email,
        ]);

        $build = 5;

        for ($i = 0; $i < $build; $i++) {
            self::$luno->sessions->create([
                'user_id' => $user['id'],
            ]);
        }

        $sessions = self::$luno->sessions->all([
            'user_id' => $user['id'],
            'expand'  => 'user',
        ]);

        foreach ($sessions as $iterator_session) {
            $this->assertTrue($iterator_session['user']['id'] === $user['id']);
        }

        self::$luno->users->destroy($user['id']);
    }

    /**
     * @return array
     */
    public function test_a_session_be_retrieved_for_a_user()
    {
        $user = self::$luno->users->create([
            'username' => self::$faker->userName,
            'name'     => self::$faker->name,
            'password' => self::$faker->password,
            'email'    => self::$faker->email,
        ]);

        $build = 5;

        for ($i = 0; $i < $build; $i++) {
            self::$luno->sessions->create([
                'user_id' => $user['id'],
            ]);
        }

        $sessions = self::$luno->sessions->recent([
            'user_id' => $user['id']
        ]);

        $this->assertCount(5, $sessions);

        foreach ($sessions as $iterator_session) {
            $this->assertTrue($iterator_session['user']['id'] === $user['id']);
        }

        self::$luno->users->destroy($user['id']);
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

    /**
     * @depends test_a_session_can_be_updated_keeping_old_details
     * @param array $session
     * @return array
     */
    public function test_a_session_can_be_updated_overriding_old_details(array $session)
    {
        $new_ip = self::$faker->ipv6;

        $new_body = [
            'ip'      => $new_ip,
            'details' => [
                'field' => 'value'
            ],
        ];

        $expected_details = [
            'field' => 'value',
        ];

        $response = self::$luno->sessions->overwrite($session['id'], $new_body);
        $fresh_session = self::$luno->sessions->find($session['id']);

        $this->assertTrue($response, "Could not append the new body.");
        $this->assertTrue($fresh_session['ip'] === $new_ip, "IP does not match the new IP.");
        $this->assertTrue($fresh_session['details'] === $expected_details, "The details have not been updated.");

        return $fresh_session;
    }

    /**
     * @depends test_a_session_can_be_updated_overriding_old_details
     * @param array $session
     */
    public function test_a_session_can_be_accessed(array $session)
    {
        $response = self::$luno->sessions->access($session['key']);

        $this->assertTrue($response['type'] === 'session');
        $this->assertTrue($response['id'] === $session['id']);

        return $response;
    }

    /**
     * @depends test_a_session_can_be_accessed
     * @param array $session
     */
    public function test_a_session_can_be_destroyed(array $session)
    {
        $response = self::$luno->sessions->destroy($session['id']);

        $this->assertTrue($response);
    }
}