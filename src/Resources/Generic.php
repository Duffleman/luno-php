<?php

namespace Duffleman\Luno\Resources;

/**
 * Class Generic
 *
 * @package Duffleman\Luno\Resources
 */
class Generic
{

    /**
     * Attributes this class is holding.
     *
     * @var array
     */
    private $attributes;

    /**
     * Generic constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->attributes = $data;
    }

    /**
     * Get a variable from $attributes.
     *
     * @param $variable
     * @return mixed
     */
    public function __get($variable)
    {
        return $this->attributes[$variable];
    }

    /**
     * Set a variable to $attributes.
     * No use case for this yet.
     *
     * @param $variable
     * @param $value
     */
    public function __set($variable, $value)
    {
        $this->attributes[$variable] = $value;
    }

    /**
     * Find if the attribute is set.
     *
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->attributes[$name]);
    }

    /**
     * Wrapper for __toString to match toArray();
     *
     * @return string
     */
    public function toString()
    {
        return $this->__toString();
    }

    /**
     * JSON encode this entire class.
     * Makes life easy for later.
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->attributes, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Return the attributes array, raw.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }
}