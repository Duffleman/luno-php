<?php

namespace Duffleman\Luno\Collections;

/**
 * Class SessionCollection
 *
 * @package Duffleman\Luno\Collections
 */
use Duffleman\Luno\Traits\CanBeScoped;

/**
 * Class SessionCollection
 *
 * @package Duffleman\Luno\Collections
 */
class SessionCollection extends BaseCollection
{

    use CanBeScoped;

    /**
     * Endpoint for this model.
     *
     * @var string
     */
    protected static $endpoint = '/sessions';

    /**
     * Validate a session key, incrementing the access count and setting the last access time.
     *
     * @param string $key
     * @param array  $body
     * @param bool   $expand
     * @return array
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     * @internal param array $params
     */
    public function access(string $key, array $body = [], bool $expand = false):array
    {
        $params = [];
        $key = compact('key');
        $body = array_merge($key, $body);

        if ($expand) {
            $params = ['expand' => 'user'];
        }

        return $this->requester->request('POST', static::$endpoint . '/access', $params, $body);
    }

    /**
     * Create a new session for this user (password not required).
     *
     * Quite messy until they change their API endpoints.
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

        return $this->requester->request('POST', "/users/{$id}/sessions", $params, $attributes);
    }
}