<?php


namespace Logistio\Symmetry\Service\Slack\Config;

/**
 * Class SlackConfig
 * @package Logistio\Symmetry\Service\Slack\Config
 */
class SlackConfig
{
    /**
     * @var string
     */
    private $webhookUrl;

    /**
     * @var boolean
     */
    private $exceptionNotificationsEnabled;

    /**
     * @var string
     */
    private $exceptionNotificationsChannel;

    /**
     * SlackConfig constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->webhookUrl = $config['webhook_url'];

        $this->exceptionNotificationsEnabled = $config['notifications']['exception']['enabled'];

        $this->exceptionNotificationsChannel = $config['notifications']['exception']['channel'];
    }

    /**
     * @return string
     */
    public function getWebhookUrl()
    {
        return $this->webhookUrl;
    }

    /**
     * @return bool
     */
    public function isExceptionNotificationsEnabled(): bool
    {
        return $this->exceptionNotificationsEnabled;
    }

    /**
     * @return string
     */
    public function getExceptionNotificationsChannel()
    {
        return $this->exceptionNotificationsChannel;
    }

}