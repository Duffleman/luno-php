<?php

namespace Duffleman\Luno\Models;

use Duffleman\Luno\Exceptions\LunoUserException;
use Duffleman\Luno\Traits\Creatable;
use Duffleman\Luno\Traits\CustomFields;
use Duffleman\Luno\Traits\Deletable;
use Duffleman\Luno\Traits\Findable;
use Duffleman\Luno\Traits\Listable;
use Duffleman\Luno\Traits\Savable;

class Session extends Base
{

    use Listable, Creatable, Findable, Savable, Deletable, CustomFields;

    protected $user_attributes;
    protected $endpoint = '/sessions';
    protected $customFieldSetName = 'details';
    protected $editableFields = [
        'ip',
        'user_agent',
        'details',
    ];

    public function setUserAttributes(array $user_attributes = [])
    {
        $this->user_attributes = $user_attributes;

        return $this;
    }

    public function __get($name)
    {
        if ($name === 'user') {
            return $this->getUser();
        }

        return parent::__get($name);
    }

    public function getUser()
    {
        if (!empty($this->user_attributes)) {
            return new User($this->requester, $this->user_attributes);
        }

        if (!empty($this->user_id)) {
            return (new User($this->requester))->find($this->user_id);
        }

        throw new LunoUserException("Cannot find a user attached to this session.");
    }

    public function updateDetails(array $data)
    {
        return $this->updateCustom($data);
    }

    public function overrideDetails(array $data)
    {
        return $this->overrideCustom($data);
    }

    public function clearDetails()
    {
        return $this->overrideCustom([]);
    }
}