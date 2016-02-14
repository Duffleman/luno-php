<?php

namespace Duffleman\Luno\Traits;


trait CustomFields
{
    public function __get($name)
    {
        if($name === $this->customFieldSetName) {
            return $this->customFieldSet;
        } else {
            return $this->updated[$name];
        }
    }

    public function __set($name, $value)
    {
        if($name === $this->customFieldSetName) {
            throw new \InvalidArgumentException("Please use a method for updating a models {$this->customFieldSetName}.");
        } else {
            $this->updated[$name] = $value;
        }
    }

    public function updateCustom(array $data) {
        return $data;
    }

    public function overrideCustom(array $data) {
        return $data;
    }
}