<?php

namespace Duffleman\Luno\Collections;

/**
 * Class UserCollection
 *
 * @package Duffleman\Luno\Collections
 */
class UserCollection extends BaseCollection
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
    public function validatePassword(string $id, string $password): bool
    {
        $body = compact('password');

        $response = $this->requester->request('POST', static::$endpoint . '/' . $id . '/password/validate', [], $body);

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
    public function changePassword(string $id, string $password, string $current_password = null): bool
    {
        $body = compact('password', 'current_password');

        $response = $this->requester->request('POST', static::$endpoint . '/' . $id . '/password/change', [], $body);

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
    public function login(string $login, string $password): array
    {
        $body = compact('login', 'password');

        return $this->requester->request('POST', static::$endpoint . '/login', [], $body);
    }
}