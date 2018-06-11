<?php

namespace Logistio\Symmetry\Notification\Exception;

use Logistio\Symmetry\Notification\Exception\Slack\SlackExceptionRenderer;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Logistio\Symmetry\Exception\BaseException;

class UnhandledExceptionNotification extends Notification
{
    private $exception;

    /**
     * UnhandledExceptionNotification constructor.
     * @param \Exception $exception
     */
    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;
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
        $renderer = new SlackExceptionRenderer($this->exception);

        return $renderer->render();
    }

    public function toArray()
    {
        if ($this->exception instanceof BaseException) {
            return $this->exception->toArray();
        }
    }

}