<?php

namespace Logistio\Symmetry\Notification\Exception\Slack;

use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Logistio\Symmetry\Service\Slack\Config\SlackConfig;
use Logistio\Symmetry\Notification\Exception\UnhandledExceptionNotificationModelInterface;

class SlackExceptionRenderer
{
    /**
     * @var UnhandledExceptionNotificationModelInterface
     */
    protected $model;

    /**
     * @var SlackConfig
     */
    private $slackConfig;

    /**
     * SlackExceptionRenderer constructor.
     * @param UnhandledExceptionNotificationModelInterface $model
     */
    public function __construct(UnhandledExceptionNotificationModelInterface $model)
    {
        $this->model = $model;

        $this->slackConfig = app()->make(SlackConfig::class);
    }

    /**
     * @return SlackMessage
     */
    public function render()
    {
        $slackMessage = new SlackMessage();

        return $slackMessage->error()
            ->from('Exception Handler')
            ->to($this->slackConfig->getExceptionNotificationsChannel())
            ->content("An exception has been thrown.")
            ->attachment(function(SlackAttachment $attachment) {
                $attachment->fields($this->model->toArray());
            })
            ->attachment(function(SlackAttachment $attachment) {
                $attachment->content($this->model->exception->getTraceAsString())
                    ->title('Stack Trace');
            });
    }
}