<?php


namespace Logistio\Symmetry\Provider\Slack;


use Illuminate\Support\ServiceProvider;
use Logistio\Symmetry\Service\Slack\Config\SlackConfig;

class SlackServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     *
     */
    public function register()
    {
        $this->app->singleton(SlackConfig::class, function($app) {
            $config = config('slack.slack');

            return new SlackConfig($config);
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [SlackConfig::class];
    }
}