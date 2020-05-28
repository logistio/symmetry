<?php


namespace Logistio\Symmetry\Util;

/**
 * Class StaticClass
 * @package Logistio\Symmetry\Util
 */
class StaticClass
{

    /**
     * StaticClass constructor.
     */
    public function __construct()
    {
        throw new \InvalidArgumentException("This class must not be instantiated.");
    }
}