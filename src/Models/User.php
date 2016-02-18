<?php

namespace Duffleman\Luno\Models;

use Duffleman\Luno\Exceptions\LunoApiException;
use Duffleman\Luno\Traits\Creatable;
use Duffleman\Luno\Traits\CustomFields;
use Duffleman\Luno\Traits\Deletable;
use Duffleman\Luno\Traits\Findable;
use Duffleman\Luno\Traits\Savable;

class User extends Base
{

    use Creatable, Findable, Savable, Deletable, CustomFields;

    protected $endpoint = '/users';
    protected $customFieldSetName = 'profile';
    protected $editableFields = [
        'email',
        'username',
        'name',
        'first_name',
        'last_name',
        'profile',
    ];

    public function updateProfile(array $data)
    {
        return $this->updateCustom($data);
    }

    public function overrideProfile(array $data)
    {
        return $this->overrideCustom($data);
    }

    public function clearProfile()
    {
        return $this->overrideCustom([]);
    }

    public function validatePassword($password)
    {
        $body = ['password' => $password];

        try {
            $this->requester->request(
                "POST",
                "{$this->endpoint}/{$this->getID()}/validatePassword",
                [],
                $body
            );
        } catch (LunoApiException $exception) {
            if ($exception->getLunoCode() === 'incorrect_password') {
                return false;
            } else {
                throw $exception;
            }
        }

        return true;
    }

    public function changePassword($password)
    {
        $body = ['password' => $password];

        $this->requester->request("POST", "{$this->endpoint}/{$this->getID()}/changePassword", [], $body);

        return true;
    }

    public function login($login, $password)
    {
        $body = ['login' => $login, 'password' => $password];

        $response = $this->requester->request("POST", "{$this->endpoint}/login", [], $body);


    }
}