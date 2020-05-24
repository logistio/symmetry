<?php


namespace Logistio\Symmetry\Validator\Env;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class DotEnvValidatorServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    public function register()
    {
        $this->registerValidator();
    }

    /**
     * Register the DotEnvValidator singleton.
     */
    protected function registerValidator()
    {
        $this->app->singleton(DotEnvValidator::class, function($app) {

            $env = env('APP_ENV');

            $config = config('deploy.env-values');


            /**
             * Check if there is a configuration for the current environment.
             */

            $envSpecificConfig = Arr::get($config, $env);

            $requiredValues = [];

            if (! is_null($envSpecificConfig) ) {

                $requiredValues = Arr::get($envSpecificConfig, 'required_values', []);

            }

            return new DotEnvValidator(
                $config['required_global'], $requiredValues
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Connection::class];
    }
}