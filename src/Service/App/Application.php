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
     * An array of closures to be executed
     * in reverse when a SIGTERM signal
     * is caught.
     *
     * @var array
     */
    private $sigtermCallbacks = [];

    /**
     * Application constructor.
     */
    public function __construct()
    {
        if ($this->supportsAsyncSignals()) {

            pcntl_async_signals(true);

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

    public function registerSigtermHandler() {
        pcntl_signal(SIGTERM, $this->getSigtermHandler());
    }

    /**
     * @return \Closure
     */
    public function getSigtermHandler() {
        return function() {
            $this->sigtermTriggered = true;

            $this->invokeSigtermCallbacks();
        };
    }

    /**
     *
     */
    public function resetSigtermHandler()
    {
        $this->registerSigtermHandler();
    }

    /**
     * @param $callback
     */
    public function addSigtermCallback($callback)
    {
        $this->sigtermCallbacks[] = $callback;
    }

    /**
     *
     */
    private function invokeSigtermCallbacks()
    {
        // Invoke the handlers in the order in which they were added (i.e. in reverse)

        $handlers = array_reverse($this->sigtermCallbacks);

        foreach ($handlers as $handler) {
            if ($handler instanceof \Closure || is_callable($handler)) {
                $handler();
            }
        }
    }
}