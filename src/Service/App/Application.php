<?php


namespace Logistio\Symmetry\Service\App;


class Application
{
    const PRODUCTION_ENVIRONMENT = 'production';
    const STAGING_ENVIRONMENT = 'staging';
    const LOCAL_ENVIRONMENT = 'local';
    const TESTING_ENVIRONMENT = 'testing';

    public $sigtermTriggered = false;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        if ($this->supportsAsyncSignals()) {
            $this->registerSigtermHandler();
        }
    }

    /**
     * @return bool
     */
    public function isProduction()
    {
        return (env('APP_ENV') === 'production');
    }

    /**
     * @return bool
     */
    public function isNotProduction()
    {
        return !$this->isProduction();
    }

    /**
     * @return bool
     */
    public function isLocal()
    {
        return (env('APP_ENV') === 'local');
    }

    /**
     * @return bool
     */
    public function isStaging()
    {
        return (env('APP_ENV') === 'staging');
    }

    /**
     * @return bool
     */
    public function isTesting()
    {
        return (env('APP_ENV') === static::TESTING_ENVIRONMENT);
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        $path = base_path() . "/composer.json";

        $json = json_decode(file_get_contents($path), true);

        return $json['version'];
    }

    public function assertEnvironmentIsValid()
    {
        $env = env('APP_ENV');

        $validEnvs = config('deploy.env-values')['valid_environments'];

        if (! in_array($env, $validEnvs) ) {
            throw new \Exception("The .env variable APP_ENV is invalid. Accepted values are: " . implode(",", $validEnvs) . " but received `{$env}`.");
        }
    }

    /**
     * Determine if "async" signals are supported.
     *
     * @return bool
     */
    public function supportsAsyncSignals()
    {
        return version_compare(PHP_VERSION, '7.1.0') >= 0 &&
            extension_loaded('pcntl');
    }

    private function registerSigtermHandler()
    {
        pcntl_signal(SIGTERM, function($sig) {
            $this->sigtermTriggered = true;
        });
    }
}