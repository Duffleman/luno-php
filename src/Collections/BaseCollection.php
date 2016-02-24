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
     * Get all resources attached to this endpoint.
     *
     * @param array $given_params
     * @return Generator
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     * @internal array $params
     */
    public function all(array $given_params = [])
    {
        do {
            $params = !empty($collection['page']['next']) ? ['from' => $collection['page']['next']['id']] : [];
            $params = array_merge($params, $given_params);

            $collection = $this->requester->request('GET', static::$endpoint, $params);
            foreach ($collection['list'] as $model) {
                yield $model;
            }
        } while (!empty($collection['page']['next']));
    }

    /**
     * @param array $params
     * @return array
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     */
    public function recent(array $params = [])
    {
        return $this->requester->request('GET', static::$endpoint, $params)['list'];
    }

    /**
     * Create a user.
     *
     * @param array  $attributes
     * @param string $expand
     * @return array
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     */
    public function create(array $attributes = [], $expand = '')
    {
        $params = [];
        if (!empty($expand)) {
            $params = compact('expand');
        }

        return $this->requester->request('POST', static::$endpoint, $params, $attributes);
    }

    /**
     * Find a user.
     *
     * @param string $id
     * @return array
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     */
    public function find($id)
    {
        return $this->requester->request('GET', static::$endpoint . '/' . $id);
    }

    /**
     * Overwrite a user (PUT).
     *
     * @param string $id
     * @param array  $body
     * @param bool   $auto_name
     * @return bool
     */
    public function overwrite($id, array $body, $auto_name = true)
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
     * @return bool
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     */
    private function update($method, $id, array $body, $auto_name = true)
    {
        $params = [];

        if (static::$endpoint === '/users') {
            $params = ['auto_name' => $auto_name];
        }

        $response = $this->requester->request($method, static::$endpoint . '/' . $id, $params, $body);

        if (isset($response['success']) && $response['success'] === true) {
            return true;
        }

        return false;
    }

    /**
     * Append a users details. (PATCH).
     *
     * @param string $id
     * @param array  $body
     * @param bool   $auto_name
     * @return bool
     */
    public function append($id, array $body, $auto_name = true)
    {
        return $this->update('PATCH', $id, $body, $auto_name);
    }

    /**
     * Destroy a user.
     *
     * @param string $id
     * @return bool
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     */
    public function destroy($id)
    {
        $response = $this->requester->request('DELETE', static::$endpoint . '/' . $id);

        if (isset($response['success']) && $response['success'] === true) {
            return true;
        }

        return false;
    }
}