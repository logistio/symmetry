<?php


namespace Logistio\Symmetry;


use Illuminate\Support\ServiceProvider;
use Logistio\Symmetry\Console\ConsoleServiceProvider;
use Logistio\Symmetry\Provider\Slack\SlackServiceProvider;
use Logistio\Symmetry\PublicId\PublicIdConverter;
use Logistio\Symmetry\PublicId\PublicIdManager;
use Logistio\Symmetry\Service\App\Application;
use Logistio\Symmetry\Util\Type\PropertyTypeCastServiceProvider;
use Logistio\Symmetry\Validator\Env\DotEnvValidatorServiceProvider;

class SymmetryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishConfigurationFiles();
    }

    /**
     *
     */
    public function register()
    {
        $this->registerProviders();

        $this->app->singleton(Application::class, function() {
            return new Application();
        });

        // PublicId
        $this->app->singleton('PublicId', function() {
            $hashIds = PublicIdManager::createHashIds();
            return new PublicIdConverter($hashIds);
        });

    }

    private function registerProviders()
    {
        \App::register(DotEnvValidatorServiceProvider::class);

        \App::register(SlackServiceProvider::class);

        \App::register(ConsoleServiceProvider::class);

        \App::register(PropertyTypeCastServiceProvider::class);
    }

    /**
     *
     */
    private function publishConfigurationFiles()
    {
        $configFilesPath = __DIR__ . '/Resources/config';

        $appSymmetryFolder = 'symmetry';

        $this->publishes([
            $configFilesPath . '/pubid_tables.php' => config_path($appSymmetryFolder . '/pubid_tables.php'),
        ]);
    }
}