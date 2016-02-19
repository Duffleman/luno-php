<?php

namespace Duffleman\Luno\Collections;

class EventCollection extends BaseCollection
{

    protected static $endpoint = '/events';

    public function create(array $attributes): array
    {
        $id = $attributes['id'];
        $expand = isset($attributes['expand']) ? $attributes['expand'] : null;
        $params = compact('expand');

        unset($attributes['id']);
        unset($attributes['expand']);

        return $this->requester->request('POST', "/users/{$id}/events", $params, $attributes);
    }
}