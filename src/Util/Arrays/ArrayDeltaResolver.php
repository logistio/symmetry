<?php

namespace Logistio\Symmetry\Util\Arrays;

use Illuminate\Support\Arr;

/**
 * Class ArrayDeltaResolver
 * @package Logistio\Symmetry\Util\Arrays
 */
class ArrayDeltaResolver
{
    /**
     * @param array $arrayToCompare
     * @param array $arrayToCompareAgainst
     * @return array
     * @throws \Exception
     */
    public static function resolveDelta(array $arrayToCompare, array $arrayToCompareAgainst)
    {
        $self = new self();

        return $self->resolve($arrayToCompare, $arrayToCompareAgainst);
    }

    /**
     * @param array $arrayToCompare
     * @param array $arrayToCompareAgainst
     * @return array
     * @throws \Exception
     */
    public function resolve(array $arrayToCompare, array $arrayToCompareAgainst)
    {
        $deltaArray = [];

        foreach ($arrayToCompare as $key => $value) {

            $correspondingValue = Arr::get($arrayToCompareAgainst, $key, null);

            if (is_null($correspondingValue)) {
                $deltaArray[$key] = $value;
                continue;
            }

            if (is_array($value)) {
                // we will perform a recursive operation, but before we do,
                // let's make sure the corresponding value is also an array

                if (!is_array($correspondingValue)) {
                    throw new \Exception("Failed to perform the delta comparison. The corresponding value for key `{$key}` is not an array.");
                }

                // Recursive call
                $deltaArray[$key] = $this->resolve($value, $correspondingValue);
                continue;
            }

            // If the corresponding value is not array, we proceed with the delta calculation

            if (!$this->isPropertyValueComparable($value)) {

                $deltaArray[$key] = null;
                continue;
            }

            if (is_null($correspondingValue) || !$this->isPropertyValueComparable($correspondingValue)) {
                $deltaArray[$key] = $value;
                continue;
            }

            $deltaArray[$key] = round(($value - $correspondingValue), 2);
        }

        return $deltaArray;
    }

    /**
     * @param $value
     * @return bool
     */
    private function isPropertyValueComparable($value)
    {
        $skip = (is_array($value) || is_null($value)) || (strlen($value) <= 0) || (!is_numeric($value));

        if ($skip) {
            return false;
        }

        return true;
    }
}