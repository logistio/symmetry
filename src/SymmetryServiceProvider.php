<?php


namespace Logistio\Symmetry;


use Illuminate\Support\ServiceProvider;
use Logistio\Symmetry\Provider\Slack\SlackServiceProvider;
use Logistio\Symmetry\PublicId\PublicIdConverter;
use Logistio\Symmetry\PublicId\PublicIdManager;
use Logistio\Symmetry\Service\App\Application;
use Logistio\Symmetry\Validator\Env\DotEnvValidatorServiceProvider;

class SymmetryServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->singleton(Application::class, function() {
            return new Application();
        });

        // PublicId
        $this->app->singleton('PublicId', function() {
            $hashIds = PublicIdManager::createHashIds();
            return new PublicIdConverter($hashIds);
        });

        \App::register(DotEnvValidatorServiceProvider::class);

        \App::register(SlackServiceProvider::class);
    }
}