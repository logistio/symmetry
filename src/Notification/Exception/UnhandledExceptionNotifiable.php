<?php

namespace Logistio\Symmetry\Notification\Exception;

use Illuminate\Notifications\Notifiable;
use Logistio\Symmetry\Service\Slack\Config\SlackConfig;

class UnhandledExceptionNotifiable
{
    use Notifiable;

    /**
     * @param UnhandledExceptionNotificationModelInterface $model
     */
    public static function notifyException(UnhandledExceptionNotificationModelInterface $model)
    {
        $instance = new self();

        return $instance->sendExceptionNotification($model);
    }

    /**
     * @param UnhandledExceptionNotificationModelInterface $model
     */
    public function sendExceptionNotification(UnhandledExceptionNotificationModelInterface $model)
    {
        $this->notify((new UnhandledExceptionNotification($model)));
    }

    public function routeNotificationForSlack()
    {
        $config = app()->make(SlackConfig::class);
        return $config->getWebhookUrl();
    }
}