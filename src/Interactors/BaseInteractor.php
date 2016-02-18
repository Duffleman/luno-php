<?php

namespace Duffleman\Luno\Interactors;

use Duffleman\Luno\LunoRequester;

class BaseInteractor
{

    protected $requester;

    public function __construct(LunoRequester $requester)
    {
        $this->requester = $requester;
    }
}