<?php

namespace Logistio\Symmetry\Exception;

use Illuminate\Http\Response;
use Throwable;

/**
 * Class ValidationException
 * @package Logistio\Symmetry\Exception
 */
class ValidationException extends BaseException implements HttpableException
{
    /**
     * @var array - Array of error message details.
     */
    protected $errorDetails;

    /**
     * ValidationException constructor.
     * @param string $message
     * @param int $code
     * @param array $errorDetails
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, $errorDetails = null, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errorDetails = $errorDetails;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $parentArray = parent::toArray();

        if ($this->errorDetails) {
            $parentArray['errors'] = $this->errorDetails;
        }

        return $parentArray;
    }

    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return Response::HTTP_BAD_REQUEST;
    }

    /**
     * @return string
     */
    public function getExceptionMessage()
    {
        return $this->getMessage();
    }
}