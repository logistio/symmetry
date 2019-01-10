<?php

namespace Logistio\Symmetry\Notification\Exception;

/**
 * Class UnhandledExceptionNotificationModel
 * @package Logistio\Symmetry\Notification\Exception
 */
class UnhandledExceptionNotificationModel implements UnhandledExceptionNotificationModelInterface
{
    /**
     * @var string
     */
    public $message;

    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $request;

    /**
     * @var string
     */
    public $user;

    /**
     * @var string
     */
    public $file;

    /**
     * @var string
     */
    public $version;

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'Message' => $this->message,
            'Code' => $this->code,
            'Request' => $this->request,
            'User' => $this->user,
            'File' => $this->file,
            'Version' => $this->version
        ];
    }

}