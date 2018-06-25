<?php


namespace Logistio\Symmetry\Util\Number;


class Number
{
    /**
     * @param $string
     * @return bool
     */
    public static function isStringInteger($string)
    {
        return ((string)(int)$string === $string);
    }
}