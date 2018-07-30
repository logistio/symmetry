<?php


namespace Logistio\Symmetry\Exception\File;

use Illuminate\Http\Response;
use Logistio\Symmetry\Exception\BaseException;
use Logistio\Symmetry\Exception\HttpableException;

class EmptyFileException extends BaseException implements HttpableException
{
    /**
     * EmptyFileException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "The file is empty.", $code = 0, Throwable $previous = null)
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