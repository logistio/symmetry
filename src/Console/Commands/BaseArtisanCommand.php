<?php

namespace Logistio\Symmetry\Console\Commands;


use Illuminate\Console\Command;
use Logistio\Symmetry\Util\Time\TimeUtil;


/**
 * BaseArtisanCommand
 * ----
 * The base command to extend when creating Artisan commands.
 *
 */
abstract class BaseArtisanCommand extends Command
{


    // ------------------------------------------------------------------------------
    // TEMPLATE

    /* *
     * The name and signature of the console command.
     *
     * @var string
     * /
    protected $signature = 'namespace:function';

    /**
     * The console command description.
     *
     * @var string
     * /
    protected $description = 'Write a description.';
    */

    // ------------------------------------------------------------------------------

    /**
     * @var bool
     */
    protected $useTransaction = true;

    /**
     * Determines whether an option arg is provided.
     * This works more reliably than the standard 'hasOption' function.
     *
     * @param $optionName
     * @return bool
     */
    protected function isOptionProvided($optionName)
    {
        $optionValue = $this->option($optionName);
        return boolval($optionValue);
    }

    protected function getBooleanOption($key)
    {
        $optionValue = $this->option($key);

        return ObjectUtil::convertToBoolean($optionValue);
    }

    /**
     * Get a mandatory option value.
     *
     * These are defined in the signature as "--argname="
     * @param $argName
     * @return string The argument value.
     * @throws \InvalidArgumentException when the arg is not provided.
     */
    private function arg($argName)
    {
        $argValue = $this->option($argName);
        if (is_null($argValue) || strlen($argValue) == 0) {
            throw new \InvalidArgumentException("The $argName argument is required.");
        }
        return $argValue;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        if ($this->useTransaction) {

            \DB::transaction(function () {
                $this->executeHandle();
            });

        } else {
            $this->warn("WARNING: This command is executing without using a transaction.");
            $this->executeHandle();
        }
    }

    abstract protected function executeHandle();


    /*
    |--------------------------------------------------------------------------
    | LOGGING
    |--------------------------------------------------------------------------
    */

    protected function infoNow($message)
    {
        $now = TimeUtil::now()->toDateTimeString();
        $this->info("{$now}: {$message}");
    }

}