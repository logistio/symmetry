<?php

namespace Logistio\Symmetry\Notification\Exception;

interface IUnhandledExceptionNotificationModelFactory
{
    /**
     * @param \Exception $exception
     * @return UnhandledExceptionNotificationModelInterface
     */
    public function makeModel(\Exception $exception): UnhandledExceptionNotificationModelInterface;
}