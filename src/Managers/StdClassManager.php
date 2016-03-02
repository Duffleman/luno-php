<?php

namespace Duffleman\Luno\Managers;


use Duffleman\Luno\Contracts\ResultManager;
use stdClass;

class StdClassManager implements ResultManager
{

    /**
     * Get teh data from the manager.
     *
     * @return mixed
     */
    public static function translate(array $data)
    {
        return new stdClass($data);
    }
}