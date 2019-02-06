<?php

namespace Logistio\Symmetry\Util;


/**
 * ObjectUtil
 * ----
 * "Object utility helper".
 *
 * This class consists of static utility methods for operating
 * on objects.  These utilities include null-safe or
 * null-tolerant methods for computing the hash code of an object,
 * returning a string for an object, and comparing two objects.
 *
 *
 * This is meant to provide functionality roughly analogous to Java's `java.util.Objects` class.
 *
 */
class ObjectUtil
{

    /**
     * Converts the [$target] to a string. Always.
     *
     * Attempts to avoid falling into "array to string conversion" exceptions
     * and exceptions caused by null arguments.
     *
     * Arrays will be converted to JSON.
     *
     * @param mixed|null $target
     * @return string
     */
    public static function toString($target)
    {
        if (is_null($target)) {
            return 'null';

        } else if (is_scalar($target) || method_exists($target, '__toString')) {
            return strval($target);

        } else if (is_array($target) || is_object($target)) {
            return json_encode($target);

        } else {
            $type = get_class($target);
            return "unknown_object:$type";
        }
    }

    /**
     * @param array|string $target
     * @return bool
     */
    public static function isEmpty($target)
    {
        if (is_array($target)) {
            return count($target) <= 0;
        } else if (is_string($target)) {
            return strlen($target) <= 0;
        } else {
            return empty($target);
        }
    }

    /**
     * @param array|string $target
     * @return bool
     */
    public static function isNotEmpty($target)
    {
        return !self::isEmpty($target);
    }

    /**
     * Converts strings that can be converted to true/false to Boolean values.
     *
     * Examples:
     * TRUE:
     *      - "true"
     *      - 1
     *      - "1"
     *      - "yes"
     *
     * FALSE:
     *      - "false"
     *      - 0
     *      - "0"
     *      - "no"
     *
     * @param string|int $stringValue
     *
     * @return bool
     */
    public static function convertToBoolean($stringValue)
    {
        return filter_var($stringValue, FILTER_VALIDATE_BOOLEAN);
    }

}