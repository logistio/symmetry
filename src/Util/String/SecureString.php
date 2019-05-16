<?php


namespace Logistio\Symmetry\Util\String;

/**
 * Class SecureString
 * @package Logistio\Symmetry\Util\String
 */
class SecureString
{
    /**
     * @var string
     */
    private $string;

    /**
     * SecureString constructor.
     * @param $string
     */
    public function __construct($string)
    {
        $this->string = $string;
    }

    /**
     * @return string
     */
    public function getString(): string {
        return $this->string;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "[SecureString]";
    }
}