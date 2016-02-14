<?php

namespace Duffleman\Luno\Traits;

trait Deletable
{

    public function destroy()
    {
        $response = $this->requester->request('DELETE', "{$this->endpoint}/{$this->getID()}");

        if ($response['success'] === true) {
            $this->updateInstance();
            return true;
        }

        return false;
    }
}