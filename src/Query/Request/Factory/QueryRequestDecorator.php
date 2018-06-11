<?php

namespace Logistio\Symmetry\Query\Request\Factory;

use Logistio\Symmetry\Exception\ValidationException;
use Logistio\Symmetry\Query\Filter\Filter;
use Logistio\Symmetry\Query\Macro\Cleaner\QueryTokenCleaner;
use Logistio\Symmetry\Query\Macro\ColumnCode\ApiColumnCodeTag;
use Logistio\Symmetry\Query\Macro\Validator\QueryRequestFilterValidator;
use Logistio\Symmetry\Query\Macro\Validator\QueryTokenValidator;
use Logistio\Symmetry\Query\Predicate\Factory\PredicateFactory;
use Logistio\Symmetry\Query\Request\Order\ColumnOrder;
use Logistio\Symmetry\Query\Request\QueryRequestInterface;
use Logistio\Symmetry\Util\Time\TimeUtil;

/**
 * Class QueryRequestDecorator
 * @package Logistio\Symmetry\Query\Request\Factory
 *
 * The base class for decorating a Query Request object
 * from the API request body.
 *
 */
class QueryRequestDecorator
{
    /**
     * @var array
     */
    protected $input;

    /**
     * @var QueryTokenCleaner
     */
    protected $queryTokenCleaner;

    /**
     * @var QueryTokenValidator
     */
    protected $queryTokenValidator;

    /**
     * @var QueryRequestFilterValidator
     */
    protected $queryRequestFilterValidator;

    public function __construct()
    {
        $this->queryTokenCleaner = new QueryTokenCleaner();

        $this->queryTokenValidator = new QueryTokenValidator();

        $this->queryRequestFilterValidator = new QueryRequestFilterValidator();
    }

    /**
     * @param QueryRequestInterface $queryRequest
     * @param array $input
     * @throws ValidationException
     * @throws \Exception
     */
    public function decorate(QueryRequestInterface $queryRequest, array $input )
    {
        $this->input = $input;

        $this->setColumnOrdering($queryRequest);

        $this->setSearchableColumns($queryRequest);

        $this->setGlobalSearchQuery($queryRequest);

        $this->setClientTimezone($queryRequest);

        $this->setPageLength($queryRequest);

        $this->setFilters($queryRequest);
    }

    /**
     * @param QueryRequestInterface $queryRequest
     * @throws ValidationException
     */
    protected function setColumnOrdering(QueryRequestInterface $queryRequest)
    {
        $input = $this->input;

        $columnOrdering = [];

        if ( isset($input['column_ordering']) ) {


            $columnsToOrder = $input['column_ordering'];

            if ( !is_array($columnsToOrder) ) {
                throw new ValidationException("The `column_ordering` parameter must be an array.");
            }

            foreach ($columnsToOrder as $index => $column) {

                $columnOrdering[] = $this->makeColumnOrderObject($column, $queryRequest, $index);

            }
        }

        $queryRequest->setColumnOrdering($columnOrdering);
    }

    /**
     * @param $column
     * @param QueryRequestInterface $queryRequest
     * @param $index
     * @return ColumnOrder
     * @throws ValidationException
     */
    private function makeColumnOrderObject($column, QueryRequestInterface $queryRequest, $index)
    {
        $direction = array_get($column, 'direction');

        if (!$direction) {
            throw new ValidationException("The `direction` property is missing from an element in the `column_ordering` property. Index: {$index}.");
        }

        if ( !ColumnOrder::isDirectionValid($direction) ) {
            throw new ValidationException("The `direction` property is invalid. Index: {$index}.");
        }

        $apiColumnCode = array_get($column, 'api_column_code');

        if (!$apiColumnCode) {
            throw new ValidationException("The `api_column_code` property is missing from an element in the `column_ordering` property. Index: {$index}.");
        }

        /** @var ApiColumnCodeTag $apiColumnCodeTag */
        $apiColumnCodeTag = array_get($queryRequest->getApiColumnCodeTagsIdx(), $apiColumnCode);

        if (!$apiColumnCodeTag) {
            throw new ValidationException("The `api_column_code` property in the `column_ordering` array is invalid. Index: {$index}.");
        }

        $columnOrderingObject = new ColumnOrder($apiColumnCode, $direction);
        $columnOrderingObject->setColumnName($apiColumnCodeTag->getDatabaseColumn());

        return $columnOrderingObject;
    }

    /**
     * @param QueryRequestInterface $queryRequest
     * @throws ValidationException
     */
    protected function setSearchableColumns(QueryRequestInterface $queryRequest)
    {
        $input = $this->input;

        $searchableColumns = [];

        if ( isset($input['searchable_columns']) ) {
            $searchableColumns = $input['searchable_columns'];

            if (!is_array($searchableColumns)) {
                throw new ValidationException("The `searchable_columns` property must be an array of searchable columns names.");
            }
        }

        $queryRequest->setSearchableColumns($searchableColumns);
    }

    /**
     * @param QueryRequestInterface $queryRequest
     * @throws ValidationException
     */
    protected function setGlobalSearchQuery(QueryRequestInterface $queryRequest)
    {
        $input = $this->input;

        $searchQuery  = null;

        if (isset($input['global_search_query'])) {
            // The request must have the `searchable_columns` property also, otherwise we cannot perform the global search.

            if (!isset($input['searchable_columns'])) {
                throw new ValidationException("The `searchable_columns` property must be an array of database columns names if the `global_search_query` property is provided.");
            }

            $searchQuery = trim($input['global_search_query']);

            if (strlen($searchQuery) == 0 ) {
                $searchQuery = null;
            }
        }

        $queryRequest->setGlobalSearchQuery($searchQuery);
    }

    /**
     * @param QueryRequestInterface $queryRequest
     * @throws ValidationException
     */
    protected function setClientTimezone(QueryRequestInterface $queryRequest)
    {
        $input = $this->input;

        $clientTimezone = 'UTC';

        if (isset($input['client_timezone'])) {
            $clientTimezone = $input['client_timezone'];

            /*
             * Validate
             */

            if (!TimeUtil::isValidTimezone($clientTimezone)) {
                throw new ValidationException("The `client_timezone` value `${clientTimezone}` is not supported.");
            }
        }

        $queryRequest->setClientTimezone($clientTimezone);
    }

    /**
     * @param QueryRequestInterface $queryRequest
     * @throws ValidationException
     */
    protected function setPageLength(QueryRequestInterface $queryRequest)
    {
        $input = $this->input;

        // Default
        $pageLength = 10;

        if (isset($input['page_length'])) {

            $pageLength  = $input['page_length'];

            /*
             * The length must be an integer
             */

            if (!is_int($pageLength)) {
                throw new ValidationException("The `page_length` parameter must be an integer.");
            }
        }

        $queryRequest->setPageLength($pageLength);
    }

    /**
     * @param QueryRequestInterface $queryRequest
     * @throws \Exception
     */
    protected function setFilters(QueryRequestInterface $queryRequest)
    {

        $input = $this->input;

        $queryFilters = [];

        if (!isset($input['filters'])) {
            return;
        }

        $filters = $input['filters'];

        if (!is_array($filters)) {
            throw new ValidationException("The `filters` property must be an array.");
        }

        foreach ($filters as $index => $filter) {

            $this->queryRequestFilterValidator->validate($filter, $index);

            $filter = $this->makeFilter($filter, $queryRequest, $index);

            if (!is_null($filter)) {
                $queryFilters[] = $filter;
            }
        }

        $queryRequest->setFilters($queryFilters);
    }

    /**
     * @param array $filter
     * @param $queryRequest
     * @param $index
     * @return array|Filter|null
     * @throws ValidationException
     * @throws \Exception
     */
    private function makeFilter(array $filter, $queryRequest, $index)
    {
        $apiColumnCodeTagsIdx = $queryRequest->getApiColumnCodeTagsIdx();

        $apiColumnCode = array_get($filter, 'api_column_code');

        $query = array_get($filter, 'query');

        $type = array_get($filter, 'type');

        $apiColumnCodeTag = array_get($apiColumnCodeTagsIdx, $apiColumnCode);

        if (is_null($apiColumnCodeTag)) {
            throw new ValidationException("The `api_column_code` property in the `filters` property is invalid. Index: {$index}.");
        }

        // Let's clean and validate the query token based on the target api column code tag.

        $query = $this->cleanQuery($query, $apiColumnCodeTag->getType());

        if ($this->shouldSkipQuery($query)) {
            return null;
        }

        try {
            $this->validateQuery($query, $apiColumnCodeTag->getType());
        } catch (ValidationException $e) {
            throw new ValidationException("Error on the `filters` property at index {$index}. " . $e->getMessage());
        }

        // Reset query
        $filter['query'] = $query;

        $predicateModel = PredicateFactory::makeFromApiJson($filter);

        $filter = new Filter();

        $filter->setApiColumnCodeTag($apiColumnCodeTag);
        $filter->setApiColumnCode($apiColumnCode);
        $filter->setType($type);
        $filter->setPredicateModel($predicateModel);

        return $filter;
    }

    /**
     * @param $query string|string[]
     * @param $type
     * @return array|float|int|string
     */
    private function cleanQuery($query, $type)
    {
        if (is_array($query)) {
            return $this->cleanQueryArray($query, $type);
        } else {
            return $this->cleanQueryToken($query, $type);
        }
    }

    /**
     * @param array $queries
     * @param $type
     * @return array
     */
    private function cleanQueryArray(array $queries, $type)
    {
        $cleanQueries = [];

        foreach ($queries as $queryToken) {

            $cleanToken =  $this->cleanQueryToken($queryToken, $type);

            if (!is_null($queryToken)) {
                $cleanQueries[] = $cleanToken;
            }
        }

        return $cleanQueries;
    }

    /**
     * @param $query string
     * @param $type
     * @return float|int|string
     */
    private function cleanQueryToken($query, $type) {
        return $this->queryTokenCleaner->clean($query, $type);
    }

    /**
     * @param $query string|string[]
     * @return bool
     */
    private function shouldSkipQuery($query)
    {
        if (is_null($query)) {
            return true;
        }

        if (is_array($query) && (count($query) == 0)) {
            return true;
        }

        return false;
    }

    /**
     * @param $query string|string[]
     * @param $type
     */
    private function validateQuery($query, $type) {
        if (is_array($query)) {
            $this->validateQueryArray($query, $type);
        } else {
            $this->validateQueryToken($query, $type);
        }
    }

    /**
     * @param array $queryTokens
     * @param $type
     */
    private function validateQueryArray(array $queryTokens, $type)
    {
        foreach ($queryTokens as $queryToken) {
            $this->validateQuery($queryToken, $type);
        }
    }

    /**
     * @param $queryToken
     * @param $type
     */
    private function validateQueryToken($queryToken, $type)
    {
        $this->queryTokenValidator->validate($queryToken, $type);
    }
}