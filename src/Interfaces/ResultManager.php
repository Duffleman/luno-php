<?php

namespace Duffleman\Luno\Interfaces;


interface ResultManager
{
    /**
     * Get teh data from the manager.
     *
     * @return mixed
     */
    public static function translate(array $data);

}