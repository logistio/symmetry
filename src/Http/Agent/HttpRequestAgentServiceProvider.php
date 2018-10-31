<?php

namespace Logistio\Symmetry\Http\Agent;

use Illuminate\Support\ServiceProvider;

class HttpRequestAgentServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(HttpRequestAgent::class, function($app) {
            $agent = $app['agent'];

            $request = $app['request'];

            $factory = new HttpRequestAgentFactory();

            return $factory->make($agent, $request);
        });
    }
}