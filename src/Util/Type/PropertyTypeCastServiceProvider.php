<?php


namespace Logistio\Symmetry\Util\Type;


use Illuminate\Support\ServiceProvider;

class PropertyTypeCastServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the provider.
     */
    public function register()
    {
        $this->app->bind(PropertyTypeCaster::class, function ($app) {
            return new PropertyTypeCaster();
        });
    }
}