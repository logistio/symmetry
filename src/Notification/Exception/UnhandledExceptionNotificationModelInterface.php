<?php


namespace Logistio\Symmetry\Notification\Exception;


interface UnhandledExceptionNotificationModelInterface
{
    /**
     * @return array
     */
    public function toArray():array;
}