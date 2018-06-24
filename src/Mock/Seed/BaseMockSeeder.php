<?php


namespace Logistio\Symmetry\Mock\Seed;

use Faker\Factory;

/**
 * Class BaseMockSeeder
 * @package Symmetry\Mock\Seed
 */
abstract class BaseMockSeeder
{
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * BaseMockSeeder constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * @var array
     */
    protected $defaultOptions = [];

    /**
     * @param  \Eloquent $entity <T in \Eloquent>
     * @return \Eloquent <T in \Eloquent>
     */
    protected function saveEntity($entity)
    {
        $entity->save();
        return $entity;
    }

    /**
     * @param $seedConfig
     * @param $paramName
     * @param Callable_|mixed $defaultValue
     *      The default value to use, or a callback to be invoked
     *      to create the default value.
     * @return mixed
     */
    protected function extractParam($seedConfig, $paramName, $defaultValue)
    {
        if (isset($seedConfig[$paramName])) {
            return $seedConfig[$paramName];

        }
        else if(is_string($defaultValue)) {
            return $defaultValue;
        }
        else if (is_callable($defaultValue)) {
            return $defaultValue->__invoke();

        } else {
            return $defaultValue;
        }
    }

    protected function mergeOptionsWithDefault($seedOptions)
    {
        return array_merge($this->defaultOptions, $seedOptions);
    }

    protected function generateUniqueCode()
    {
        return uniqid();
    }

    protected function generateUniqueGlobalId()
    {
        $randMax = getrandmax();
        $min = $randMax - 1e5;
        return rand($min, $randMax);
    }

    /**
     * @param array $selection
     * @return mixed
     */
    protected function chooseRandomlyFrom($selection)
    {
        $index = rand(0, sizeof($selection) - 1);
        return $selection[$index];
    }

    /**
     *
     * @param array $intervalConfig - Configuration which specifies that a certain key
     * must have a certain value when the current index matches the interval. This
     * is used when seeding multiple objects and you would like to alternate
     * between values at some interval. For example you may wish to seed
     * 10 bookings where every 5th booking will have a status of cancelled
     * and the rest will have a status of completed.
     *
     * @param $seedOptions
     * @param $currentIndex
     * @return mixed
     */
    protected function setSeedOptionValueFromIntervalConfig(array $seedOptions, array $intervalConfig, $currentIndex)
    {
        $key = $intervalConfig['key'];
        $atIndexInterval = $intervalConfig['at_index_interval'];
        $atIndexValue = $intervalConfig['at_index_value'];
        $defaultValue = $intervalConfig['default_value'];

        $seedOptions[$key] = ($currentIndex % $atIndexInterval == 0) ? $atIndexValue : $defaultValue;

        return $seedOptions;
    }

}