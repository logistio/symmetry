<?php


namespace Logistio\Symmetry\Exception\System;


use Illuminate\Http\Response;
use Logistio\Symmetry\Exception\BaseException;
use Logistio\Symmetry\Exception\HttpableException;

class ExecutionInterruptedException extends BaseException implements HttpableException
{
    /**
     * ExecutionInterruptedException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Execution process has been interrupted at the OS level.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    /**
     * @return string
     */
    public function getExceptionMessage()
    {
        return $this->getMessage();
    }
}