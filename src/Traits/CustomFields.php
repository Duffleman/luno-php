<?php

namespace Duffleman\Luno\Traits;

use stdClass;

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

        return $this->requester->request('PATCH', "{$this->endpoint}/{$this->getID()}", [], $data);
    }

    public function overrideCustom(array $data = [])
    {
        if (empty($data)) {
            $data = [
                $this->customFieldSetName => new stdClass(),
            ];

            $this->customFields = [];

            return $this->requester->request('PUT', "{$this->endpoint}/{$this->getID()}", [], $data);
        }

        $this->customFields = $data;

        $this->updated[$this->customFieldSetName] = $this->customFields;

        $data = [
            $this->customFieldSetName => $this->customFields
        ];

        return $this->requester->request('PUT', "{$this->endpoint}/{$this->getID()}", [], $data);
    }
}