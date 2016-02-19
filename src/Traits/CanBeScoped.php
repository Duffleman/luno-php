<?php

namespace Duffleman\Luno\Traits;

/**
 * Class CanBeScoped
 *
 * @package Duffleman\Luno\Traits
 */
use Generator;

/**
 * Class CanBeScoped
 *
 * @package Duffleman\Luno\Traits
 */
trait CanBeScoped
{

    /**
     * Scope array to apply to results.
     *
     * @var array
     */
    protected $scope = [];

    /**
     * Set the scope.
     *
     * @param array $scope_attributes
     * @return $this
     */
    public function where(array $scope_attributes)
    {
        $this->scope = $scope_attributes;

        return $this;
    }

    /**
     * Clear the scope.
     *
     * @return void
     */
    public function clearScope(): void
    {
        $this->scope = [];
    }

    /**
     * Overwrite recent() function to allow for scoping.
     *
     * @param int|null    $limit
     * @param string|null $from
     * @param string|null $to
     * @return array
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

            return $this->requester->request('GET', "/users/{$user_id}" . static::$endpoint, $params)['list'];
        }

        return parent::recent($limit, $from, $to);
    }

    /**
     * Is a scope applied?
     *
     * @return bool
     */
    public function isScoped(): bool
    {
        if (!empty($this->scope)) {
            return true;
        }

        return false;
    }

    /**
     * Overwrite all() function to allow for scoping.
     *
     * @return Generator
     */
    public function all(): Generator
    {
        $user_id = $this->scope['user.id'];

        if ($this->isScoped()) {
            do {
                $params = !empty($collection['page']['next']) ? ['from' => $collection['page']['next']['id']] : [];
                $params['expand'] = 'user';
                $collection = $this->requester->request('GET', "/users/{$user_id}" . static::$endpoint,
                    $params)['list'];

                foreach ($collection['list'] as $model) {
                    yield $model;
                }
            } while (!empty($collection['page']['next']));

            return true;
        }

        return parent::all();
    }
}