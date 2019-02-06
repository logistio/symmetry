<?php

namespace Logistio\Symmetry\Exception\Auth;

use Illuminate\Http\Response;
use Logistio\Symmetry\Exception\BaseException;
use Logistio\Symmetry\Exception\HttpableException;

/**
 * InvalidCredentialsException
 * ----
 *
 *
 */
class InvalidCredentialsException extends BaseException implements HttpableException
{
    /**
     * InvalidCredentialsException constructor.
     * @param string $message
     * @param int $code
     * @param null $internalCode
     * @param null $previous
     */
    public function __construct($message = "Invalid credentials.", $code = 0, $internalCode = null, $previous = null)
    {
        parent::__construct($message, $code, $internalCode, $previous);
    }

    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return Response::HTTP_BAD_REQUEST;
    }
}