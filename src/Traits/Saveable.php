<?php

namespace Duffleman\Luno\Traits;


use Duffleman\Luno\Exceptions\LunoUserException;

trait Saveable
{

    public function save() {
        $updatedKeys = [];

        foreach($this->original as $key => $value)
        {
            if($this->updated[$key] !== $value && in_array($key, $this->editableFields)) {
                $updatedKeys[] = $key;
            }
        }

        $newUserData = [];
        foreach($updatedKeys as $key) {
            $newUserData[$key] = $this->updated[$key];
        }

        $this->updateCustomAttributes();

        if(count($newUserData) === 0) {
            return false;
        }

        $response = $this->requester->request('PUT', "{$this->endpoint}/{$this->original['id']}", [], $newUserData);

        if($response['success'] === true) {
            $this->populateModel($this->updated);
            return true;
        } else {
            throw new LunoUserException("Unable to update the model.");
        }
    }

    protected function updateCustomAttributes() {
        if($this->checkIfUsesCustomFields()) {
            // do stuff with the custom field set.
        }
    }

    protected function checkIfUsesCustomFields()
    {
        $traits = class_uses($this);
        if(array_key_exists("Duffleman\\Luno\\Traits\\CustomFields", $traits)) {
            return true;
        }

        return false;
    }

}