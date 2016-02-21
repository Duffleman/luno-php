<?php

use Duffleman\Luno\LunoRequester;

class UserCollectionTest extends PHPUnit_Framework_TestCase
{

    protected $luno;
    protected $faker;

    public function setUp()
    {
        $dotenv = new Dotenv\Dotenv(__DIR__ . '/..');
        $dotenv->load();

        $this->faker = Faker\Factory::create();

        $this->luno = new LunoRequester([
            'key'     => getenv('LUNO_KEY'),
            'secret'  => getenv('LUNO_SECRET'),
            'timeout' => 10000
        ]);
    }

    public function test_can_list_recent_100_users()
    {
        $users = $this->luno->users->recent();

        $this->assertLessThanOrEqual(100, count($users));
    }

    public function test_user_can_be_created()
    {
        $user_details = $this->buildFakeUser();

        $user = $this->luno->users->create($user_details);

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
    private function buildFakeUser(): array
    {
        return [
            'username' => $this->faker->userName,
            'name'     => $this->faker->name,
            'email'    => $this->faker->email,
            'password' => $this->faker->password,
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

        $imported_user = $this->luno->users->find($user_id);

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
        $new_name = $this->faker->name;
        $updated_details = [
            'name'    => $new_name,
            'profile' => [
                'attribute' => 'value',
            ]
        ];

        $this->luno->users->append($user['id'], $updated_details);
        $updated_user = $this->luno->users->find($user['id']);

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
        $this->luno->users->overwrite($user['id'], [
            'profile' => (object)[]
        ]);
        $fresh_user = $this->luno->users->find($user['id']);

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
        $this->luno->users->changePassword($user['id'], 'myPassword');

        $response = $this->luno->users->validatePassword($user['id'], 'myPassword');

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

        $change_password_response = $this->luno->users->changePassword($user['id'], $new_password, 'myPassword');
        $validate_password_response = $this->luno->users->validatePassword($user['id'], $new_password);

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
        $response = $this->luno->users->validatePassword($user['id'], 'myNewPassword');

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
        $this->luno->users->changePassword($user['id'], 'myNewPassword');

        $session = $this->luno->users->login($user['username'], 'myNewPassword');

        $this->assertTrue($session['session']['user']['id'] === $user['id']);

        return $session['user'];
    }

    /**
     * @depends test_user_can_login
     * @param array $user
     */
    public function test_user_can_be_deactivated(array $user)
    {
        $response = $this->luno->users->destroy($user['id']);

        $user = $this->luno->users->find($user['id']);

        $this->assertNotNull($user['closed']);
        $this->assertTrue($response);
    }

}