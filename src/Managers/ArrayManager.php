<?php

namespace Duffleman\Luno\Managers;


use Duffleman\Luno\Contracts\ResultManager;

final class ArrayManager implements ResultManager
{
    /**
     * Get the data from the manager.
     *
     * @return mixed
     */
    public static function translate(array $data)
    {
        return $data;
    }
}