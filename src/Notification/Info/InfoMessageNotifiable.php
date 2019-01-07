<?php


namespace Logistio\Symmetry\Notification\Info;


use Illuminate\Notifications\Notifiable;
use Logistio\Symmetry\Service\Slack\Config\SlackConfig;

class InfoMessageNotifiable
{
    use Notifiable;

    /**
     * @param array $payload
     */
    public static function notifyMessage(array $payload)
    {
        $instance = new self();

        return $instance->sendNotification($payload);
    }

    /**
     * @param array $payload
     */
    public function sendNotification(array $payload)
    {
        $this->notify((new InfoMessageNotification($payload)));
    }

    public function routeNotificationForSlack()
    {
        $config = app()->make(SlackConfig::class);
        return $config->getWebhookUrl();
    }
}