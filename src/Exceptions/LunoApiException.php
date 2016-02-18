<?php

namespace Duffleman\Luno\Exceptions;

use Exception;

class LunoApiException extends Exception
{

    protected $luno_code;
    protected $luno_status;
    protected $luno_extra;

    public function __construct(array $error)
    {
        parent::__construct($error['message']);
        $this->luno_code = $error['code'];
        $this->luno_status = $error['status'];
        if (array_key_exists('extra', $error)) {
            $this->luno_extra = $error['extra'];
        }
    }

    public function getLunoCode()
    {
        return $this->luno_code;
    }

    public function getLunoStatus()
    {
        return $this->luno_status;
    }

    public function getLunoExtra()
    {
        return $this->luno_extra;
    }
}