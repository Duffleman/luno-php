<?php

namespace Duffleman\Luno\Traits;

trait CustomFields
{

    public function __get($name)
    {
        if ($name === $this->customFieldSetName) {
            return $this->customFieldSet;
        } else {
            return $this->updated[$name];
        }
    }

    public function __set($name, $value)
    {
        if ($name === $this->customFieldSetName) {
            throw new \InvalidArgumentException("Please use a method for updating a models {$this->customFieldSetName}.");
        } else {
            $this->updated[$name] = $value;
        }
    }

    public function updateCustom(array $data)
    {
        foreach ($data as $key => $value) {
            $this->customFields[$key] = $value;
        }

        $this->updated[$this->customFieldSetName] = $this->customFields;

        $data = [
            $this->customFieldSetName => $this->customFields
        ];

        return $this->requester->request('PATCH', "{$this->endpoint}/{$this->original['id']}", [], $data);
    }

    public function overrideCustom(array $data)
    {
        $this->customFields = $data;

        $this->updated[$this->customFieldSetName] = $this->customFields;

        $data = [
            $this->customFieldSetName => $this->customFields
        ];

        return $this->requester->request('PUT', "{$this->endpoint}/{$this->original['id']}", [], $data);
    }
}