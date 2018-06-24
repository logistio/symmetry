<?php
/* Copyright (C) 2018, Logistio Limited. All Rights Reserved. */


namespace Logistio\Symmetry\Exception\Config;

/**
 * InvalidEnvException
 * ----
 * Thrown when an env variable was found to be incorrectly configured.
 *
 */
class InvalidEnvException extends \RuntimeException
{

    public static function configNotFound($varName): InvalidEnvException
    {
        return new InvalidEnvException("The `$varName` env parameter is missing.");
    }

}