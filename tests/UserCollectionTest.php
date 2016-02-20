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

        $dotenv->required('LUNO_KEY');
        $dotenv->required('LUNO_SECRET');

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

    public function test_user_can_be_created_and_deactivated()
    {
        $user_details = $this->buildFakeUser();

        $user = $this->luno->users->create($user_details);
        $response = $this->luno->users->destroy($user['id']);

        $this->assertTrue($user_details['username'] === $user['username']);
        $this->assertTrue($user_details['name'] === $user['name']);
        $this->assertTrue($user_details['email'] === $user['email']);
        $this->assertTrue($response['success']);
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

    public function test_user_can_be_found_by_its_id()
    {
        $created_user = $this->createFakeUser();
        $user = $this->luno->users->find($created_user['id']);
        $this->luno->users->destroy($user['id']);

        $this->assertTrue($created_user['id'] === $user['id']);
    }

    /**
     * Creates a fake user, persists it too.
     *
     * @param array $details
     * @return array
     */
    private function createFakeUser(array $details = []): array
    {
        $user = empty($details) ? $this->buildFakeUser() : $details;

        return $this->luno->users->create($user);
    }

    public function test_user_can_be_updated()
    {
        $created_user = $this->createFakeUser();

        $new_name = $this->faker->name;
        $updated_details = [
            'name'    => $new_name,
            'profile' => [
                'attribute' => 'value',
            ]
        ];

        $this->luno->users->append($created_user['id'], $updated_details);
        $updated_user = $this->luno->users->find($created_user['id']);

        $this->assertTrue($updated_user['name'] === $new_name);
        $this->assertTrue($updated_user['profile']['attribute'] === 'value');

        return $updated_user['id'];
    }

    /**
     * @depends test_user_can_be_updated
     * @param string $user_id
     */
    public function test_user_can_be_updated_destructively(string $user_id)
    {
        $this->luno->users->overwrite($user_id, [
            'profile' => (object)[]
        ]);
        $user = $this->luno->users->find($user_id);
        $this->luno->users->destroy($user['id']);

        $this->assertEmpty($user['profile']);
    }

    public function test_user_can_login()
    {
        $details = $this->buildFakeUser();
        $user = $this->createFakeUser($details);

        $session = $this->luno->users->login($details['username'], $details['password']);
        $this->luno->users->destroy($user['id']);

        $this->assertTrue($session['session']['user']['id'] === $user['id']);
    }

    public function test_user_can_validate_password()
    {
        $details = $this->buildFakeUser();
        $user = $this->createFakeUser($details);

        $response = $this->luno->users->validatePassword($user['id'], $details['password']);

        $this->assertTrue($response);
    }

    public function test_user_can_change_password()
    {
        $details = $this->buildFakeUser();
        $user = $this->createFakeUser($details);
        $this->luno->users->validatePassword($user['id'], $details['password']);

        $new_password = $this->faker->password;
        $this->luno->users->changePassword($user['id'], $new_password);

        $response = $this->luno->users->validatePassword($user['id'], $new_password);

        $this->assertTrue($response);
    }

    public function test_user_can_change_password_with_current_password()
    {
        $details = $this->buildFakeUser();
        $user = $this->createFakeUser($details);

        $new_password = $this->faker->password;

        $change_password_response = $this->luno->users->changePassword($user['id'], $new_password, $details['password']);
        $validate_password_response = $this->luno->users->validatePassword($user['id'], $new_password);

        $this->assertTrue($change_password_response);
        $this->assertTrue($validate_password_response);
    }

}