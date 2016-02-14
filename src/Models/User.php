<?php

namespace Duffleman\Luno\Models;

use Duffleman\Luno\Traits\Creatable;
use Duffleman\Luno\Traits\CustomFields;
use Duffleman\Luno\Traits\Findable;
use Duffleman\Luno\Traits\Savable;

class User extends Base
{

    use Creatable, Findable, Savable, CustomFields;

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
}