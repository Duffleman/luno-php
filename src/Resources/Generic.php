<?php

namespace Duffleman\Luno\Resources;


class Generic
{

    private $attributes;

    public function __construct(array $data = [])
    {
        $this->attributes = $data;
    }

    public function __get($variable)
    {
        return $this->attributes[$variable];
    }

    public function __set($variable, $value)
    {
        $this->attributes[$variable] = $value;
    }

    public function __isset($name)
    {
        return isset($this->attributes[$name]);
    }

    public function __toString()
    {
        return json_encode($this->attributes, JSON_UNESCAPED_SLASHES);
    }

    public function toArray()
    {
        return $this->attributes;
    }
}