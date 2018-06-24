<?php

namespace Logistio\Symmetry\Exception;

/**
 * NotImplementedException
 * ----
 * Marks a function as being not implemented, or being in "TODO" status.
 *
 *
 *
 * ----
 * @package App\Exceptions
 */
class NotImplementedException extends \RuntimeException
{

    /**
     * @param string $message
     * @throws NotImplementedException
     */
    public static function TODO($message = '[NO MESSAGE]')
    {
        throw new NotImplementedException("TODO: $message");
    }

    /**
     * Throws a "NotImplementedException" for a class method which has not been implemented.
     *
     * The Exception message will read:
     *  "ClassName::methodName is not implemented."
     *
     *
     * @param $className
     * @param $methodName
     */
    public static function throwForClassMethod($className, $methodName)
    {
        throw new NotImplementedException("$className::$methodName is not implemented.");
    }

}