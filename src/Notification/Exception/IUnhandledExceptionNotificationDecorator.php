<?php

namespace Logistio\Symmetry\Notification\Exception;

interface IUnhandledExceptionNotificationDecorator
{
    /**
     * @param UnhandledExceptionNotificationModelInterface $model
     * @param \Exception $exception
     * @return mixed
     */
    public function decorate(UnhandledExceptionNotificationModelInterface $model, \Exception $exception);
}