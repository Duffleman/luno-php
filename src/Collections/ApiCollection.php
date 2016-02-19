<?php

namespace Duffleman\Luno\Collections;

use Duffleman\Luno\Traits\CanBeScoped;

class ApiCollection extends BaseCollection
{

    use CanBeScoped;

    protected static $endpoint = '/api_authentication';

    public function create(array $attributes): array
    {
        $id = $attributes['id'];
        $expand = isset($attributes['expand']) ? $attributes['expand'] : null;
        $params = compact('expand');

        unset($attributes['id']);
        unset($attributes['expand']);

        return $this->requester->request('POST', "/users/{$id}/api_authentication", $params, $attributes);
    }

}