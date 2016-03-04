<?php

namespace Duffleman\Luno\Managers;


use Duffleman\Luno\Contracts\ResultManager;
use Duffleman\Luno\Resources\Generic;

final class GenericClassManager implements ResultManager
{

    /**
     * Get teh data from the manager.
     *
     * @param array $data
     * @return Generic
     */
    public static function translate(array $data)
    {
        if(!static::has_string_keys($data))
        {
            $set = [];
            foreach($data as $item) {
                $set[] = new Generic($item);
            }

            return $set;
        }

        return new Generic($data);
    }

    /**
     * Detect if an array has stringed keys rather than zero indexed.
     *
     * @param array $array
     * @return bool
     */
    private static function has_string_keys(array $array) {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }
}