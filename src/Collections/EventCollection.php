<?php

namespace Duffleman\Luno\Collections;

/**
 * Class EventCollection
 *
 * @package Duffleman\Luno\Collections
 */
class EventCollection extends BaseCollection
{

    /**
     * Endpoint for this model.
     *
     * @var string
     */
    protected static $endpoint = '/events';

    /**
     * Create a new event.
     *
     * @param array $attributes
     * @return array
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     */
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