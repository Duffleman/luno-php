<?php

namespace Duffleman\Luno\Traits;

trait Creatable
{

    public function create(array $attributes)
    {
        $model = $this->requester->request('POST', $this->endpoint, [], $attributes);
        $this->populateModel($model);

        return $this;
    }
}