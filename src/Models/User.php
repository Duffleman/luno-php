<?php

namespace Duffleman\Luno\Models;

use Duffleman\Luno\Exceptions\LunoUserException;
use Duffleman\Luno\Traits\Creatable;
use Duffleman\Luno\Traits\Findable;
use Duffleman\Luno\Traits\Saveable;

class User extends Base
{
    use Creatable, Findable, Saveable;

    protected $endpoint = '/users';

    protected $old_profile;
    protected $new_profile;

    protected $editableFields = [
        'email',
        'username',
        'name',
        'first_name',
        'last_name',
    ];

    public function __get($name)
    {
        if($name === 'profile') {
            return $this->new_profile;
        } else {
            return $this->updated[$name];
        }
    }

    public function __set($name, $value)
    {
        if($name === 'profile') {
            throw new \InvalidArgumentException("Please use the updateProfile() method for updating a users profile.");
        } else {
            $this->updated[$name] = $value;
        }
    }

    public function updateProfile(array $data) {
        foreach($data as $key => $value) {

        }
    }

    public function overrideProfile(array $data)
    {

    }
}