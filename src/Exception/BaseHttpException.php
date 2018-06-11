<?php


namespace Logistio\Symmetry\Exception;

/**
 * Class BaseHttpException
 */
class BaseHttpException extends BaseException
{
    /**
     * @var stringApp
     */
    protected $httpStatusCode;

    public function __construct($statusCode, $message = "", $code = 0, $internalCode = null, $previous = null)
    {
        parent::__construct($message, $code, $internalCode, $previous);

        $this->httpStatusCode = $statusCode;
    }

    /**
     * @return string
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }
}