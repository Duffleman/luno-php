<?php

namespace Duffleman\Luno\Interactors;

class AccountInteractor extends BaseInteractor
{

    /**
     * Endpoint for this model.
     *
     * @var string
     */
    protected static $endpoint = '/account';

    public function get()
    {
        return $this->requester->request('GET', static::$endpoint);
    }

    public function update(array $new_details)
    {
        return $this->requester->request('PUT', self::$endpoint, [], $new_details);
    }
}