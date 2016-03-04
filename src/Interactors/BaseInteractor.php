<?php

namespace Duffleman\Luno\Interactors;

use Duffleman\Luno\LunoRequester;

/**
 * Class BaseInteractor
 *
 * @package Duffleman\Luno\Interactors
 */
abstract class BaseInteractor
{

    /**
     * Holds the base HTTP requester.
     *
     * @var LunoRequester
     */
    protected $requester;

    /**
     * BaseInteractor constructor.
     *
     * @param LunoRequester $requester
     */
    public function __construct(LunoRequester $requester)
    {
        $this->requester = $requester;
    }
}