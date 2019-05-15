<?php

namespace Logistio\Symmetry\Query\Macro\Util;

use Logistio\Symmetry\Exception\ValidationException;
use Logistio\Symmetry\Query\Filter\Filter;
use Logistio\Symmetry\Query\Macro\ColumnCode\ApiColumnCodeTag;
use Logistio\Symmetry\Query\Request\Order\ColumnOrder;
use Logistio\Symmetry\Query\Request\QueryRequestInterface;

trait QueryBuildingUtilities
{
    use DbStringSearchTrait;

    /**
     * @return QueryRequestInterface
     */
    public function getQueryRequest(): QueryRequestInterface
    {
        return $this->queryRequest;
    }

    /**
     * @param ApiColumnCodeTag $apiColumnCodeTag
     * @return string
     */
    protected function createRawWhereClauseForGlobalSearch(ApiColumnCodeTag $apiColumnCodeTag)
    {
        if ($apiColumnCodeTag->isTypeDatetime()) {
            return $this->makeGlobalSearchStringForDateTimeColumn($apiColumnCodeTag->getDatabaseColumn());
        }
//        if (in_array($searchableColumn, $this->encodedIdColumns) && $searchQueryDecoded) {
//            return $this->makeStrictGlobalSearchStringComparison($searchableColumn);
//        }

        return $this->makeDefaultGlobalSearchStringForColumn($apiColumnCodeTag->getDatabaseColumn());
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

    /**
     * Create the global search query string.
     *
     * @param Builder $queryBuilder
     */
    protected function setGlobalSearchQuery($queryBuilder)
    {
        $searchQuery = $this->queryRequest->getGlobalSearchQuery();

        if (is_null($searchQuery)) {
            return;
        }

        $searchableColumns = $this->queryRequest->getSearchableColumns();

        $apiColumnCodeTagsIdx = $this->queryRequest->getApiColumnCodeTagsIdx();

        $queryBuilder->where(function($query) use ($searchQuery, $searchableColumns, $apiColumnCodeTagsIdx) {

            foreach ($searchableColumns as $searchableColumn) {

                // The $searchableColumn variable corresponds to the `api_column_code` model
                // value for a query request.
                // We must get the actual data source column defined defined in the
                // $globalSearchableColumnsMap

                /** @var ApiColumnCodeTag $apiCodeTag */
                $apiCodeTag = array_get($apiColumnCodeTagsIdx, $searchableColumn);

                if (!$apiCodeTag) {
                    throw new ValidationException("The api column code `{$searchableColumn}` is invalid.");
                }

                $whereRaw = $this->createRawWhereClauseForGlobalSearch($apiCodeTag);

                $query->orWhereRaw($whereRaw, [
                    $this->escapeQueryToken($searchQuery)
                ]);
            }
        });
    }

    /**
     * @override
     * @param Builder|\Illuminate\Database\Query\Builder $queryBuilder
     */
    protected function setColumnOrdering($queryBuilder)
    {
        /** @var ColumnOrder $columnOrder */
        foreach ($this->queryRequest->getColumnOrdering() as $columnOrder) {

            $queryBuilder->orderByRaw("{$columnOrder->getColumnName()} {$columnOrder->getDirection()} NULLS LAST");

        }

    }

    protected function setFilters($queryBuilder)
    {
        if (!$this->queryRequest->getFilters()) {
            return;
        }

        $apiColumnCodeTagsIdx = $this->queryRequest->getApiColumnCodeTagsIdx();

        /** @var Filter $filter */
        foreach ($this->queryRequest->getFilters() as $filter) {

            /** @var ApiColumnCodeTag $apiColumnTag */
            $apiColumnTag = array_get($apiColumnCodeTagsIdx, $filter->getApiColumnCode());

            if (is_null($apiColumnTag)) {
                throw new ValidationException("The api column code `{$apiColumnTag}` is invalid.");
            }

            $filter->attachWhereClauseToQueryBuilder($queryBuilder, $this->queryRequest);
        }
    }

    /**
     * @param $queryBuilder
     */
    protected function setSelectColumns($queryBuilder) {
        if (!$columns = $this->queryRequest->getSelectColumns()) {
            return;
        }

        $queryBuilder->select($columns);
    }
}