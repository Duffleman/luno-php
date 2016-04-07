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
}