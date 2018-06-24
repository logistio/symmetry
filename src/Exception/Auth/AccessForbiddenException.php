<?php

namespace Logistio\Symmetry\Exception\Auth;

use Illuminate\Http\Response;
use Logistio\Symmetry\Exception\BaseException;
use Logistio\Symmetry\Exception\HttpableException;
use Throwable;

class AccessForbiddenException extends BaseException implements HttpableException
{
    /**
     * UnauthorizedException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return Response::HTTP_FORBIDDEN;
    }

    /**
     * @return string
     */
    public function getExceptionMessage()
    {
        return $this->getMessage();
    }
}