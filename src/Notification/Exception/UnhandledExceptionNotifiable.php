<?php

namespace Logistio\Symmetry\Notification\Exception;

use Illuminate\Notifications\Notifiable;
use Logistio\Symmetry\Service\Slack\Config\SlackConfig;

class UnhandledExceptionNotifiable
{
    use Notifiable;

    /**
     * @param \Exception $e
     */
    public static function notifyException(\Exception $e)
    {
        $instance = new self();

        return $instance->sendExceptionNotification($e);
    }

    /**
     * @param \Exception $e
     */
    public function sendExceptionNotification(\Exception $e)
    {
        $this->notify((new UnhandledExceptionNotification($e)));
    }

    public function routeNotificationForSlack()
    {
        $config = app()->make(SlackConfig::class);
        return $config->getWebhookUrl();
    }
}