<?php
namespace Duffleman\Luno\Traits;

trait Findable
{

    public function find($id_string)
    {
        $user = $this->requester->request('GET', "{$this->endpoint}/{$id_string}");
        $this->populateModel($user);

        return $this;
    }

}