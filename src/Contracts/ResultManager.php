<?php

namespace Duffleman\Luno\Contracts;


interface ResultManager
{
    /**
     * Get the data from the manager.
     *
     * @return mixed
     */
    public static function translate(array $data);

}