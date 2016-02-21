<?php

namespace Duffleman\Luno\Collections;

use Duffleman\Luno\Traits\CanBeScoped;

class ApiCollection extends BaseCollection
{

    use CanBeScoped;

    protected static $endpoint = '/api_authentication';

}