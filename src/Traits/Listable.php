<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 18/02/2016
 * Time: 17:28
 */

namespace Duffleman\Luno\Traits;

trait Listable
{

    public function listAll()
    {
        return $this->requester->request("GET", "{$this->endpoint}");
    }
}