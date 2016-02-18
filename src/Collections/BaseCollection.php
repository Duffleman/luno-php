<?php

namespace Duffleman\Luno\Collections;

use Duffleman\Luno\Interactors\BaseInteractor;
use Generator;

/**
 * Class BaseCollection
 *
 * @package Duffleman\Luno\Collections
 */
class BaseCollection extends BaseInteractor
{

    /**
     * Defines the endpoint for this model.
     *
     * @var string
     */
    protected static $endpoint;

    /**
     * @return Generator
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     */
    public function all(): Generator
    {
        do {
            $params = !empty($collection['page']['next']) ? ['from' => $collection['page']['next']['id']] : [];

            $collection = $this->requester->request('GET', static::$endpoint, $params);
            foreach ($collection['list'] as $model) {
                yield $model;
            }
        } while (!empty($collection['page']['next']));

        return $collection;
    }

    /**
     * @param int|null    $limit
     * @param string|null $from
     * @param string|null $to
     * @return array
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     */
    public function recent(int $limit = null, string $from = null, string $to = null): array
    {
        $params = [
            'from'  => $from,
            'limit' => $limit,
            'to'    => $to,
        ];

        return $this->requester->request('GET', static::$endpoint, $params)['list'];
    }

    /**
     * Create a user.
     *
     * @param array $attributes
     * @return array
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     */
    public function create(array $attributes): array
    {
        return $this->requester->request('POST', static::$endpoint, [], $attributes);
    }

    /**
     * Find a user.
     *
     * @param string $id
     * @return array
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     */
    public function find(string $id): array
    {
        return $this->requester->request('GET', static::$endpoint . '/' . $id);
    }

    /**
     * Overwrite a user (PUT).
     *
     * @param string $id
     * @param array  $body
     * @param bool   $auto_name
     * @return array
     */
    public function overwrite(string $id, array $body, $auto_name = true): array
    {
        return $this->update('PUT', $id, $body, $auto_name);
    }

    /**
     * Update a user for PUT and PATCH.
     * Avoids code duplication.
     *
     * @param string $method
     * @param string $id
     * @param array  $body
     * @param bool   $auto_name
     * @return array
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     */
    private function update(string $method, string $id, array $body, $auto_name = true): array
    {
        $params = ['auto_name' => $auto_name];

        return $this->requester->request($method, static::$endpoint . '/' . $id, $params, $body);
    }

    /**
     * Append a users details. (PATCH).
     *
     * @param string $id
     * @param array  $body
     * @param bool   $auto_name
     * @return array
     */
    public function append(string $id, array $body, $auto_name = true): array
    {
        return $this->update('PATCH', $id, $body, $auto_name);
    }

    /**
     * Destroy a user.
     *
     * @param string $id
     * @return array
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     */
    public function destroy(string $id): array

    {
        return $this->requester->request('DELETE', static::$endpoint . '/' . $id);
    }
}