<?php
namespace Duffleman\Luno\Traits;

trait Findable
{

    public function find($id_string)
    {
        $model = $this->requester->request('GET', "{$this->endpoint}/{$id_string}");
        $this->populateModel($model);

        if ($this->usesCustom()) {
            $this->customFields = $model[$this->customFieldSetName];
        }

        return $this;
    }
}