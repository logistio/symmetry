<?php

namespace Logistio\Symmetry\Util\String;

/**
 * Class StringUtil
 * @package Logistio\Symmetry\Util\String\StringUtil
 */
class StringUtil
{
    /**
     * Remove the first occurrence of $char in the
     * string $string.
     *
     * @param $char
     * @param $string
     * @return null|string|string[]
     */
    public static function removeFirstOccurrenceOfChar($char, $string)
    {
        $regex = "/^[^{$char}]*{$char}\s*/";

        return preg_replace($regex, '', $string);
    }
}