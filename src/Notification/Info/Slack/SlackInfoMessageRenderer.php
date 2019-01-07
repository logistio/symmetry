<?php


namespace Logistio\Symmetry\Notification\Info\Slack;


use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Logistio\Symmetry\Service\Slack\Config\SlackConfig;

class SlackInfoMessageRenderer
{
    /**
     * @var array
     */
    private $payload;

    /**
     * @var SlackConfig
     */
    private $slackConfig;

    /**
     * SlackInfoMessageRenderer constructor.
     * @param array $payload
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;

        $this->slackConfig = app()->make(SlackConfig::class);
    }

    /**
     * @return SlackMessage
     */
    public function render()
    {
        $slackMessage = new SlackMessage();

        return $slackMessage->info()
            ->from('Info Handler')
            ->to($this->slackConfig->getInfoNotificationsChannel())
            ->content(array_get($this->payload, 'message', 'Info Message'))
            ->attachment(function(SlackAttachment $attachment) {
                $attachment->fields(array_get($this->payload, 'meta', []));
            });
    }
}