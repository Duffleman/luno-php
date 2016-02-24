<?php

use Duffleman\Luno\LunoRequester;

class UserCollectionTest extends PHPUnit_Framework_TestCase
{

    protected static $luno;
    protected static $faker;

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
    }

    public function test_can_list_recent_100_users()
    {
        $users = self::$luno->users->recent();

        $this->assertLessThanOrEqual(100, count($users));
    }

    public function test_user_can_be_created()
    {
        $user_details = $this->buildFakeUser();

        $user = self::$luno->users->create($user_details);

        $this->assertTrue($user_details['username'] === $user['username']);
        $this->assertTrue($user_details['name'] === $user['name']);
        $this->assertTrue($user_details['email'] === $user['email']);

        return $user;
    }

    /**
     * Builds a fake user.
     *
     * @return array
     */
    private function buildFakeUser()
    {
        return [
            'username' => self::$faker->userName,
            'name'     => self::$faker->name,
            'email'    => self::$faker->email,
            'password' => self::$faker->password,
        ];
    }

    /**
     * @depends test_user_can_be_created
     * @param array $user
     * @return array
     */
    public function test_user_can_be_found_by_its_id(array $user)
    {
        $user_id = $user['id'];

        $imported_user = self::$luno->users->find($user_id);

        $this->assertTrue($user_id === $imported_user['id']);

        return $imported_user;
    }

    /**
     * @depends  test_user_can_be_found_by_its_id
     * @param array $user
     * @return array
     */
    public function test_user_can_be_updated(array $user)
    {
        $new_name = self::$faker->name;
        $updated_details = [
            'name'    => $new_name,
            'profile' => [
                'attribute' => 'value',
            ]
        ];

        self::$luno->users->append($user['id'], $updated_details);
        $updated_user = self::$luno->users->find($user['id']);

        $this->assertTrue($updated_user['name'] === $new_name);
        $this->assertTrue($updated_user['profile']['attribute'] === 'value');

        return $updated_user;
    }

    /**
     * @depends  test_user_can_be_updated
     * @param array $user
     * @return array
     */
    public function test_user_can_be_updated_destructively(array $user)
    {
        self::$luno->users->overwrite($user['id'], [
            'profile' => []
        ]);
        $fresh_user = self::$luno->users->find($user['id']);

        $this->assertEmpty($fresh_user['profile']);

        return $fresh_user;
    }

    /**
     * @depends test_user_can_be_updated_destructively
     * @param array $user
     * @return array
     */
    public function test_user_can_change_password(array $user)
    {
        self::$luno->users->changePassword($user['id'], 'myPassword');

        $response = self::$luno->users->validatePassword($user['id'], 'myPassword');

        $this->assertTrue($response);

        return $user;
    }

    /**
     * @depends test_user_can_change_password
     * @param array $user
     * @return array
     */
    public function test_user_can_change_password_with_current_password(array $user)
    {
        $new_password = 'myNewPassword';

        $change_password_response = self::$luno->users->changePassword($user['id'], $new_password, 'myPassword');
        $validate_password_response = self::$luno->users->validatePassword($user['id'], $new_password);

        $this->assertTrue($change_password_response);
        $this->assertTrue($validate_password_response);

        return $user;
    }

    /**
     * @depends test_user_can_change_password_with_current_password
     * @param array $user
     * @return array
     */
    public function test_user_can_validate_password(array $user)
    {
        $response = self::$luno->users->validatePassword($user['id'], 'myNewPassword');

        $this->assertTrue($response);

        return $user;
    }

    /**
     * @depends test_user_can_validate_password
     * @param array $user
     * @return array
     */
    public function test_user_can_login(array $user)
    {
        self::$luno->users->changePassword($user['id'], 'myNewPassword');

        $session = self::$luno->users->login($user['username'], 'myNewPassword');

        $this->assertTrue($session['session']['user']['id'] === $user['id']);

        return $session['user'];
    }

    /**
     * @depends test_user_can_login
     * @param array $user
     */
    public function test_user_can_be_deactivated(array $user)
    {
        $response = self::$luno->users->destroy($user['id']);

        $user = self::$luno->users->find($user['id']);

        $this->assertNotNull($user['closed']);
        $this->assertTrue($response);
    }

}