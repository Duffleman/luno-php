<?php

namespace Duffleman\Luno\Collections;

/**
 * Class UserCollection
 *
 * @package Duffleman\Luno\Collections
 */
final class UserCollection extends BaseCollection
{

    /**
     * Define the endpoint for this model.
     *
     * @var string
     */
    protected static $endpoint = '/users';

    /**
     * Check that a password is correct, without logging the user in.
     *
     * @param string $id
     * @param string $password
     * @return bool
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     */
    public function validatePassword($id, $password)
    {
        $body = compact('password');

        $response = $this->requester->request('POST', static::$endpoint . '/' . $id . '/password/validate', [], $body, true);

        if ($response['success']) {
            return true;
        }

        return false;
    }

    /**
     * Change or set a users password.
     *
     * @param string      $id
     * @param string      $password
     * @param string|null $current_password
     * @return bool
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     */
    public function changePassword($id, $password, $current_password = null)
    {
        $body = compact('password', 'current_password');

        $response = $this->requester->request('POST', static::$endpoint . '/' . $id . '/password/change', [], $body, true);

        if ($response['success']) {
            return true;
        }

        return false;
    }

    /**
     * Log in a user by id, email or username.
     *
     * @param string $login
     * @param string $password
     * @return array
     */
    public function login($login, $password)
    {
        $body = compact('login', 'password');

        return $this->requester->request('POST', static::$endpoint . '/login', [], $body);
    }

    /**
     * Exactly the same as login() but you pass in an array of credentials rather than specify
     * the arguments.
     *
     * @param array $credentials
     * @return mixed
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     */
    public function loginByCredentials(array $credentials)
    {
        return $this->requester->request('POST', static::$endpoint . '/login', [], $credentials);
    }

    public function reactivate($id)
    {
        return $this->requester->request('POST', static::$endpoint . '/' . $id . '/reactivate');
    }

    public function destroy($id, $permanent = false)
    {
        $headers = [];
        if ($permanent) {
            $headers = compact('permanent');
        }

        $response = $this->requester->request('DELETE', static::$endpoint . '/' . $id, $headers, [], true);

        if (isset($response['success']) && $response['success'] === true) {
            return true;
        }

        return false;
    }
}