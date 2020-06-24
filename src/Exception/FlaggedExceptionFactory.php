<?php


namespace Logistio\Symmetry\Exception;


/**
 * FlagException
 * ----
 * Creates BaseExceptions that contain a flag.
 *
 * The constants defined in this class are intended to represent
 * the entire enumerable set of internal error codes (i.e. "flags")
 * that are used by Symmetry to report specific errors.
 *
 * The higher-level Exception Handler can look for the specific
 * flags and respond to them accordingly.
 *
 */
class FlaggedExceptionFactory
{
    const FLAG_INVALID_PUBLIC_ID = 'INVALID_PUBLIC_ID';

    // ----------------------------------------------------

    public static function createWithFlag($flag): BaseException
    {
        return new BaseException("ErrorFlag=$flag", $flag);
    }

    public static function createWithMessageAndFlag($message, $flag): BaseException
    {
        return new BaseException($message, $flag);
    }

}