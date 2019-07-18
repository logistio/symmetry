<?php


namespace Logistio\Symmetry\Util\Object;



/**
 * Consumable Trait
 * ----
 * An object which is Consumable may only be used until it has been consumed.
 * If once attempts to perform an operation that consumes a Consumable
 * after it has already been consumed then the Consumable will throw
 * an exception.
 *
 */
trait Consumable
{
    /**
     * @var bool
     */
    private $isFullyConsumed = false;

    /**
     * Checks if this Consumable has been consumed.
     *
     * @return bool
     */
    protected function isConsumed(): bool
    {
        return $this->isFullyConsumed;
    }

    /**
     * Marks this Consumable as consumed, if it has not already been consumed,
     * otherwise throws an exception.
     *
     * @throws \BadMethodCallException
     */
    protected function consume()
    {
        if ($this->isConsumed()) {
            throw new \BadMethodCallException("An instance of a Consumable may only be used once.");
        } else {
            $this->isFullyConsumed = true;
        }
    }

}