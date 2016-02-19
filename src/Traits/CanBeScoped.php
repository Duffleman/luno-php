<?php

namespace Duffleman\Luno\Traits;

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
     * Clear the scope.
     *
     * @return void
     */
    public function clearScope(): void
    {
        $this->scope = [];
    }
}