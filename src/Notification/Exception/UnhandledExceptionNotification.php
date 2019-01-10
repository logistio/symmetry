<?php

namespace Logistio\Symmetry\Notification\Exception;

use Logistio\Symmetry\Notification\Exception\Slack\SlackExceptionRenderer;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class UnhandledExceptionNotification extends Notification
{
    /**
     * @var UnhandledExceptionNotificationModelInterface
     */
    protected $model;

    /**
     * UnhandledExceptionNotification constructor.
     * @param UnhandledExceptionNotificationModelInterface $model
     */
    public function __construct(UnhandledExceptionNotificationModelInterface $model)
    {
        $this->model = $model;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * @param $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $renderer = new SlackExceptionRenderer($this->model);

        return $renderer->render();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->model->toArray();
    }

}