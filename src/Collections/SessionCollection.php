<?php

namespace Duffleman\Luno\Collections;

/**
 * Class SessionCollection
 *
 * @package Duffleman\Luno\Collections
 */
class SessionCollection extends BaseCollection
{

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
     * @param array  $params
     * @param bool   $expand
     * @return array
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
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
}