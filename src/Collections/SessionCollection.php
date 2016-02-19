<?php

namespace Duffleman\Luno\Collections;

/**
 * Class SessionCollection
 *
 * @package Duffleman\Luno\Collections
 */
use Duffleman\Luno\Traits\CanBeScoped;
use Generator;

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

    public function all(): Generator
    {
        $user_id = $this->scope['user.id'];

        if ($this->isScoped()) {
            do {
                $params = !empty($collection['page']['next']) ? ['from' => $collection['page']['next']['id']] : [];
                $params['expand'] = 'user';
                $collection = $this->requester->request('GET', "/users/{$user_id}/sessions", $params)['list'];

                foreach ($collection['list'] as $model) {
                    yield $model;
                }
            } while (!empty($collection['page']['next']));

            return true;
        }

        return parent::all();
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

    /**
     * Load recent sessions that may be scoped to a specific user.
     *
     * @param int|null    $limit
     * @param string|null $from
     * @param string|null $to
     * @return array
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     */
    public function recent(int $limit = null, string $from = null, string $to = null): array
    {
        if ($this->isScoped()) {
            $params = [
                'limit' => $limit,
                'from'  => $from,
                'to'    => $to,
            ];

            $user_id = $this->scope['user.id'];

            return $this->requester->request('GET', "/users/{$user_id}/sessions", $params)['list'];
        }

        return parent::recent($limit, $from, $to);
    }
}