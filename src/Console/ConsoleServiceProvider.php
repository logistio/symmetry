<?php


namespace Logistio\Symmetry\Console;

use Illuminate\Support\ServiceProvider;
use Logistio\Symmetry\Console\Commands\Database\PublicId\TablePubIdSetterCommand;

/**
 * Class ConsoleServiceProvider
 * @package Logistio\Symmetry\Console
 */
class ConsoleServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                TablePubIdSetterCommand::class
            ]);
        }
    }
}