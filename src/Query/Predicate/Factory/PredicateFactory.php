<?php

namespace Logistio\Symmetry\Query\Predicate\Factory;

use Logistio\Symmetry\Query\Predicate\Equals;
use Logistio\Symmetry\Query\Predicate\GreaterThan;
use Logistio\Symmetry\Query\Predicate\GreaterThanOrEqualTo;
use Logistio\Symmetry\Query\Predicate\In;
use Logistio\Symmetry\Query\Predicate\LessThan;
use Logistio\Symmetry\Query\Predicate\LessThanOrEqualTo;
use Logistio\Symmetry\Query\Predicate\Max;
use Logistio\Symmetry\Query\Predicate\Min;
use Logistio\Symmetry\Query\Predicate\NotEquals;
use Logistio\Symmetry\Query\Predicate\NotIn;
use Logistio\Symmetry\Query\Predicate\PredicateModel;
use Logistio\Symmetry\Query\Predicate\Range;
use Logistio\Symmetry\Query\Predicate\StrictEquals;
use Logistio\Symmetry\Query\Predicate\StrictNotEquals;

class PredicateFactory
{
    /**
     * @param $apiJson
     * @return PredicateModel
     * @throws \Exception
     */
    public static function makeFromApiJson($apiJson)
    {
        $self = new self();

        return $self->makeFromArray($apiJson);
    }

    /**
     * @param array $data:
     *  api_column_code
     *  type
     *  query (string or array<string>)
     *
     * @return PredicateModel
     * @throws \Exception
     */
    public function makeFromArray(array $data)
    {
        $type = $data['type'];

        $query = $data['query'];

        switch ($type) {

            case PredicateModel::TYPE_EQUALS: {
                return $this->makeForTypeEquals($query);
            }

            case PredicateModel::TYPE_NOT_EQUALS: {
                return $this->makeForTypeNotEquals($query);
            }

            case PredicateModel::TYPE_STRICT_EQUALS: {
                return $this->makeForTypeStrictEquals($query);
            }

            case PredicateModel::TYPE_STRICT_NOT_EQUALS: {
                return $this->makeForTypeStrictNotEquals($query);
            }

            case PredicateModel::TYPE_MAX: {
                return $this->makeForTypeMax($query);
            }

            case PredicateModel::TYPE_MIN: {
                return $this->makeForTypeMin($query);
            }

            case PredicateModel::TYPE_LESS_THAN: {
                return $this->makeForTypeLessThan($query);
            }

            case PredicateModel::TYPE_LESS_THAN_OR_EQUAL_TO: {
                return $this->makeForTypeLessThanOrEqualTo($query);
            }

            case PredicateModel::TYPE_GREATER_THAN: {
                return $this->makeForTypeGreaterThan($query);
            }

            case PredicateModel::TYPE_GREATER_THAN_OR_EQUAL_TO: {
                return $this->makeForTypeGreaterThanOrEqualTo($query);
            }

            case PredicateModel::TYPE_RANGE: {
                return $this->makeForTypeRange($query);
            }

            case PredicateModel::TYPE_IN: {
                return $this->makeForTypeIn($query);
            }

            case PredicateModel::TYPE_NOT_IN: {
                return $this->makeForTypeNotIn($query);
            }

            default:
                throw new \Exception("The predicate filter of type `{$type}` is invalid.");
        }
    }

    /**
     * @param $query
     * @return Equals
     */
    private function makeForTypeEquals($query) {
        return new Equals($query);
    }

    /**
     * @param $query
     * @return NotEquals
     */
    private function makeForTypeNotEquals($query) {
        return new NotEquals($query);
    }

    /**
     * @param $query
     * @return StrictEquals
     */
    private function makeForTypeStrictEquals($query)
    {
        return new StrictEquals($query);
    }

    /**
     * @param $query
     * @return StrictNotEquals
     */
    private function makeForTypeStrictNotEquals($query)
    {
        return new StrictNotEquals($query);
    }

    /**
     * @param $query
     * @return Max
     */
    private function makeForTypeMax($query)
    {
        return new Max($query);
    }

    /**
     * @param $query
     * @return Min
     */
    private function makeForTypeMin($query)
    {
        return new Min($query);
    }

    /**
     * @param $queries
     * @return Range
     */
    private function makeForTypeRange($queries)
    {
        return new Range($queries);
    }

    /**
     * @param $queries
     * @return In
     */
    private function makeForTypeIn($queries)
    {
        return new In($queries);
    }

    /**
     * @param $queries
     * @return NotIn
     */
    private function makeForTypeNotIn($queries)
    {
        return new NotIn($queries);
    }

    /**
     * @param $query
     * @return LessThan
     */
    private function makeForTypeLessThan($query)
    {
        return new LessThan($query);
    }

    /**
     * @param $query
     * @return LessThanOrEqualTo
     */
    private function makeForTypeLessThanOrEqualTo($query)
    {
        return new LessThanOrEqualTo($query);
    }

    /**
     * @param $query
     * @return GreaterThan
     */
    private function makeForTypeGreaterThan($query)
    {
        return new GreaterThan($query);
    }

    /**
     * @param $query
     * @return GreaterThanOrEqualTo
     */
    private function makeForTypeGreaterThanOrEqualTo($query)
    {
        return new GreaterThanOrEqualTo($query);
    }
}