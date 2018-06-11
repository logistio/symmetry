<?php

namespace Logistio\Symmetry\Exception;

use Illuminate\Support\Arr;
use Throwable;
use Illuminate\Http\Response;
use Illuminate\Contracts\Support\Arrayable;
use Logistio\Symmetry\Service\App\Application;

/**
 * Class BaseException
 *
 */
class BaseException extends \Exception implements Arrayable, HttpableException
{
    /**
     * @var string
     */
    protected $internalCode;

    /**
     * @var string
     */
    protected $errorFlag;

    /**
     * @var Application
     */
    protected $application;

    /**
     * BaseException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param string $internalCode
     */
    public function __construct($message = "", $internalCode = null, Throwable $previous = null, $code = 0)
    {
        parent::__construct($message, $code, $previous);

        $this->internalCode = $internalCode;

        $this->errorFlag = null;

        $this->application = new Application();
    }

    /**
     * @param string $flag
     */
    protected function setErrorFlag($flag)
    {
        $this->errorFlag = $flag;
    }

    /**
     * @return bool
     */
    public function hasInternalCode()
    {
        return (!is_null($this->internalCode));
    }

    /**
     * @return null
     */
    public function getInternalCode()
    {
        return $this->internalCode;
    }

    /**
     * @return boolean
     */
    public function hasErrorFlag()
    {
        return (!is_null($this->errorFlag));
    }

    /**
     * @return string
     */
    public function getErrorFlag()
    {
        return $this->errorFlag;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = [
            'message' => $this->getExceptionMessage(),
            'internal_code' => $this->hasInternalCode() ? $this->getInternalCode() : null,
            'error_flag' => $this->hasErrorFlag() ? $this->getErrorFlag() : null
        ];

        /*
         * If the application is not in production mode,
         * we may display a more detailed error
         * data structure.
         */
        if ($this->application->isNotProduction()) {
            $data = array_merge($data, [
                'exception' => get_class($this),
                'file' => $this->getFile(),
                'line' => $this->getLine(),
                'trace' => collect($this->getTrace())->map(function ($trace) {
                    return Arr::except($trace, ['args']);
                })->all(),
            ]);
        }

        return $data;
    }

    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function toJsonResponse()
    {
        return response()->json([
            'error' => $this->toArray()
        ], $this->getHttpStatusCode());
    }

    /**
     * @return string
     */
    public function getExceptionMessage()
    {
        $defaultMessage =  "An unexpected error has occurred.";

        return $this->getMessage() ? $this->getMessage() : $defaultMessage;
    }
}