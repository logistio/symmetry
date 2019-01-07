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
     * @var string
     */
    private $infoNotificationsChannel;

    /**
     * SlackConfig constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->webhookUrl = $config['webhook_url'];

        $this->exceptionNotificationsEnabled = $config['notifications']['exception']['enabled'];

        $this->exceptionNotificationsChannel = $config['notifications']['exception']['channel'];

        $this->infoNotificationsChannel = $config['notifications']['info']['channel'];
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

    /**
     * @return string
     */
    public function getInfoNotificationsChannel()
    {
        return $this->infoNotificationsChannel;
    }

}