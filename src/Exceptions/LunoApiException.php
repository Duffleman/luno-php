<?php

namespace Duffleman\Luno\Exceptions;

use Exception;

class LunoApiException extends Exception
{

    /**
     * The string code Luno uses to quickly describe the problem.
     *
     * @var string
     */
    protected $luno_code;

    /**
     * The integer HTTP status code that is returned with the error.
     *
     * @var integer
     */
    protected $luno_status;

    /**
     * An array of extra parameters.
     *
     * @var array
     */
    protected $luno_extra;

    /**
     * LunoApiException constructor.
     *
     * @param array $error
     */
    public function __construct(array $error)
    {
        parent::__construct($error['message']);
        $this->luno_code = $error['code'];
        $this->luno_status = $error['status'];
        if (array_key_exists('extra', $error)) {
            $this->luno_extra = $error['extra'];
        }
    }

    /**
     * Getter for Luno Code
     *
     * @return string
     */
    public function getLunoCode()
    {
        return $this->luno_code;
    }

    /**
     * Getter for the Luno Status
     *
     * @return integer
     */
    public function getLunoStatus()
    {
        return $this->luno_status;
    }

    /**
     * Getter for the Luno extra array.
     *
     * @return array
     */
    public function getLunoExtra()
    {
        return $this->luno_extra;
    }
}