<?php

namespace Duffleman\Luno\Collections;

use Duffleman\Luno\Interactors\BaseInteractor;
use Generator;

class BaseCollection extends BaseInteractor
{

    protected static $endpoint;

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

    public function recent(int $limit = null, string $from = null, string $to = null): array
    {
        $params = [
            'from'  => $from,
            'limit' => $limit,
            'to'    => $to,
        ];

        return $this->requester->request('GET', static::$endpoint, $params)['list'];
    }

    public function create(array $attributes): array
    {
        return $this->requester->request('POST', static::$endpoint, [], $attributes);
    }

    public function find(string $id): array
    {
        return $this->requester->request('GET', static::$endpoint . '/' . $id);
    }

    public function overwrite(string $id, array $body, $auto_name = true): array
    {
        return $this->update('PUT', $body, $auto_name);
    }

    private function update(string $method, string $id, array $body, $auto_name = true): array
    {
        $params = ['auto_name' => $auto_name];

        return $this->requester->request('PUT', static::$endpoint . '/' . $id, $params, $body);
    }

    public function append(string $id, array $body, $auto_name = true): array
    {
        return $this->update('PATCH', $body, $auto_name);
    }

    public function destroy(string $id): array

    {
        return $this->requester->request('DELETE', static::$endpoint . '/' . $id);
    }
}