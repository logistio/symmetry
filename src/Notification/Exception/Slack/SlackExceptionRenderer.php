<?php

namespace Logistio\Symmetry\Notification\Exception\Slack;

use Logistio\Symmetry\Service\App\Application;
use Logistio\Symmetry\Service\Slack\Config\SlackConfig;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Logistio\Symmetry\Auth\UserAuthProvider;

class SlackExceptionRenderer
{
    /**
     * @var  \Exception
     */
    private $exception;

    /**
     * @var Application
     */
    private $application;

    /**
     * @var SlackConfig
     */
    private $slackConfig;

    /**
     * SlackExceptionRenderer constructor.
     * @param \Exception $exception
     */
    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;

        $this->application = app()->make(Application::class);

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
                $attachment->fields([
                    'Message' =>  $this->exception->getMessage(),
                    'Code'  => $this->exception->getCode(),
                    'Request' => request()->path(),
                    'User'  => $this->getUserEmail(),
                    'File'  => $this->exception->getFile() . " at line " . $this->exception->getLine(),
                    'Version' => $this->application->getVersion(),
                ]);
            })
            ->attachment(function(SlackAttachment $attachment) {
                $attachment->content($this->exception->getTraceAsString())
                    ->title('Stack Trace');
            });
    }

    /**
     * @return null|string
     */
    private function getUserEmail()
    {
        $user = UserAuthProvider::getAuthUser();

        if ($user) {
            return $user->email;
        }

        return null;
    }
}