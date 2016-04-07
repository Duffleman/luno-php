<?php

namespace Duffleman\Luno\Exceptions;

use Exception;

final class LunoApiException extends Exception
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
     * The human readable description that is returned with the error.
     *
     * @var string
     */
    protected $luno_description;

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
        $this->luno_description = $error['description'];
        if (array_key_exists('extra', $error)) {
            $this->luno_extra = $error['extra'];
        }
    }

    /**
     * Alias for the getLunoDesc() function.
     *
     * @return string
     */
    public function getLunoDescription()
    {
        return $this->getLunoDesc();
    }

    /**
     * Getter for the Luno Description
     *
     * @return string
     */
    public function getLunoDesc()
    {
        return $this->luno_description;
    }

    /**
     * Super easy way of showing everything for debugging.
     *
     * @param bool $extra
     * @return array
     */
    public function getAll($extra = false)
    {
        $error = [
            'code'        => $this->getLunoCode(),
            'message'     => $this->getMessage(),
            'description' => $this->getLunoDesc(),
            'status'      => $this->getLunoStatus(),
        ];

        if($extra)
        {
            $error['extra'] = $this->getLunoExtra();
        }

        return $error;
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