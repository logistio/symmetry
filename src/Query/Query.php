<?php

namespace Logistio\Symmetry\Query;

use Logistio\Symmetry\Query\Request\QueryRequestInterface;
use Logistio\Symmetry\Query\Macro\Util\DbStringSearchTrait;
use Illuminate\Support\Collection;

/**
 * Class BaseQuery
 * @package Logistio\Symmetry\Query
 *
 * Abstract class representing a Query.
 *
 *
 * The classic use case is `server-side` pagination, ordering, filtering etc...
 * for displaying large amounts of data in a table on the client side.
 *
 */
abstract class Query
{
    /**
     * Make use of the database searching
     * utility trait.
     */
    use DbStringSearchTrait;

    /**
     * The QueryRequest represents all the options required
     * for executing the Query.
     *
     * @var QueryRequestInterface
     */
    protected $queryRequest;

    /**
     * @var Collection
     */
    protected $apiColumnCodeTags;

    /**
     * @var array|Collection
     */
    protected $apiColumnCodeTagsIdx;

    /**
     * Query constructor.
     * @param $queryRequest
     */
    public function __construct($queryRequest)
    {
        $this->queryRequest = $queryRequest;

        $this->apiColumnCodeTags = $queryRequest->getApiColumnCodeTags();

        $this->apiColumnCodeTagsIdx = $queryRequest->getApiColumnCodeTagsIdx();
    }


    /**
     * @param $searchableColumn
     * @param $searchQueryDecoded
     * @return string
     */
    protected function createRawWhereClauseForGlobalSearch($searchableColumn, $searchQueryDecoded)
    {
        // If the column is a date time column, we require a different search query.
        if (in_array($searchableColumn, $this->dateTimeColumns)) {
            return $this->makeGlobalSearchStringForDateTimeColumn($searchableColumn);
        }

        if (in_array($searchableColumn, $this->encodedIdColumns) && $searchQueryDecoded) {
            return $this->makeStrictGlobalSearchStringComparison($searchableColumn);
        }

        return $this->makeDefaultGlobalSearchStringForColumn($searchableColumn);
    }

    /**
     * For the global search query, return the default
     * string.
     *
     * Eg. LOWER( CAST(product.price as TEXT)) LIKE ?
     *
     * @param $columnName
     * @return string
     */
    protected function makeDefaultGlobalSearchStringForColumn($columnName)
    {
        return "LOWER( CAST( {$columnName} as TEXT)) LIKE ?";
    }

    /**
     * @param $columnName
     * @return string
     */
    protected function makeStrictGlobalSearchStringComparison($columnName)
    {
        return "LOWER( CAST( {$columnName} as TEXT)) = ?";
    }

    /**
     * Make the global search query for a column
     * that is of type DATETIME(TIMESTAMP).
     *
     * @param $columnName
     * @return string
     */
    protected function makeGlobalSearchStringForDateTimeColumn($columnName)
    {
        return "TO_CHAR({$columnName} AT time zone '{$this->queryRequest->getClientTimezone()}', 'YYYY-MM-DD HH24:MI:SS') LIKE ?";
    }
}