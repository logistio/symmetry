<?php


namespace Logistio\Symmetry\Query\Macro\Validator;

use Illuminate\Support\Arr;
use Logistio\Symmetry\Exception\ValidationException;
use Logistio\Symmetry\Query\Predicate\PredicateModel;

/**
 * Class QueryRequestFilterValidator
 * @package Logistio\Symmetry\Query\Macro\Validator
 *
 * Validate an element of `filters` API query request body.
 */
class QueryRequestFilterValidator
{
    /**
     * @param array $filter
     * @param $index
     * @throws ValidationException
     */
    public function validate(array $filter, $index)
    {
        $apiColumnCode = Arr::get($filter, 'api_column_code');

        $type = Arr::get($filter, 'type');

        if (!$apiColumnCode) {
            throw new ValidationException("The `api_column_code` property is missing from an element in the `filters` property. Index: {$index}.");
        }

        if (!$type) {
            throw new ValidationException("The `type` property is missing from an element in the `filters` property. Index: {$index}.");
        }

        if (!PredicateModel::isTypeValid($type)) {
            throw new ValidationException("The `type` property `{$type}` is invalid in an element in the `filters` property. Index: {$index}.");
        }

        if (!array_key_exists('query', $filter)) {
            throw new ValidationException("The `query` property is missing from an element in the `filters` property. Index: {$index}.");
        }

        $query = $filter['query'];

        $this->validateQueryForPredicateType($query, $type, $index);
    }

    /**
     * @param $query
     * @param $predicateType
     * @param $index
     * @throws ValidationException
     */
    private function validateQueryForPredicateType($query, $predicateType, $index)
    {
        if (PredicateModel::isTypeMultipleQueryPredicate($predicateType)) {

            // Make sure the query is an array

            $this->validateForMultipleQueryPredicate($query, $predicateType, $index);

        } else {
            $this->validateForSingleQueryPredicate($query, $predicateType, $index);
        }
    }

    /**
     * @param $query
     * @param $predicateType
     * @param $index
     * @throws ValidationException
     */
    private function validateForSingleQueryPredicate($query, $predicateType, $index)
    {
        if (is_array($query)) {
            throw new ValidationException("Invalid `query` property at `filter` index {$index}. The `query` property must be a single primitive value for the type {$predicateType}, but an array was given.");
        }
    }

    /**
     * @param $query
     * @param $predicateType
     * @param $index
     * @throws ValidationException
     */
    private function validateForMultipleQueryPredicate($query, $predicateType, $index)
    {
        if (!is_array($query)) {
            throw new ValidationException("Invalid `query` property at `filter` index {$index}. The `query` property must be an array of query tokens (numbers or strings) for the type {$predicateType}.");
        }

        // If the array is empty, we do NOT throw an exception as the filter will be simply ignored.

        if (count($query) == 0) {
            return;
        }

        // Otherwise, if the predicate is of type `RANGE`, the query must be composed
        // of exactly 2 elements.

        if (PredicateModel::TYPE_RANGE == $predicateType) {

            $elementsCount = count($query);

            if ($elementsCount != 2) {
                throw new ValidationException("Invalid `query` property at `filter` index {$index}. The `query` property must be an array of exactly 2 elements for the type {$predicateType}.");
            }

        }
    }
}