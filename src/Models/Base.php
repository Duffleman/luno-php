<?php

namespace Duffleman\Luno\Models;

use Duffleman\Luno\LunoRequester;

class Base
{
    protected $requester;

    protected $original;
    protected $updated;

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
}