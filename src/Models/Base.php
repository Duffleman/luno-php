<?php

namespace Duffleman\Luno\Models;

use Duffleman\Luno\LunoRequester;

class Base
{

    protected $requester;

    protected $original;
    protected $updated;

    protected $customFieldSetName = null;

    protected $customFields = [];

    protected $editableFields = [];

    public function __construct(LunoRequester $requester)
    {
        $this->requester = $requester;
    }

    public function toArray()
    {
        return (array)$this->updated;
    }

    public function populateModel(array $attributes)
    {
        $this->original = $attributes;
        $this->updated = $attributes;
    }

    public function __get($name)
    {
        return $this->updated[$name];
    }

    public function __set($name, $value)
    {
        $this->updated[$name] = $value;
    }
}