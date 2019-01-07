<?php


namespace Logistio\Symmetry\Notification\Info;


use Logistio\Symmetry\Notification\Info\Slack\SlackInfoMessageRenderer;

class InfoMessageNotification
{
    /**
     * @var array
     */
    private $payload;

    /**
     * GenericMessageNotification constructor.
     * @param array $payload
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
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
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $renderer = new SlackInfoMessageRenderer($this->payload);

        return $renderer->render();
    }
}