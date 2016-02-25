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
     * Wrapper for access();
     *
     * @param string $key
     * @param array  $body
     * @param string $expand
     * @return array
     */
    public function validate($key, array $body = [], $expand = '')
    {
        return $this->access($key, $body, $expand);
    }

    /**
     * Validate a session key, incrementing the access count and setting the last access time.
     *
     * @param string $key
     * @param array  $body
     * @param string $expand
     * @return array
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     * @internal param array $params
     */
    public function access($key, array $body = [], $expand = '')
    {
        $key = compact('key');
        $body = array_merge($key, $body);

        $params = [];
        if (!empty($expand)) {
            $params = compact('expand');
        }

        return $this->requester->request('POST', static::$endpoint . '/access', $params, $body);
    }
}