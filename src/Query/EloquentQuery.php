<?php

namespace Logistio\Symmetry\Query;

use Logistio\Symmetry\Exception\ValidationException;
use Illuminate\Database\Eloquent\Collection;
use Logistio\Symmetry\Query\Filter\Filter;
use Logistio\Symmetry\Query\Macro\ColumnCode\ApiColumnCodeTag;
use Illuminate\Database\Eloquent\Builder;
use Logistio\Symmetry\Query\Request\Order\ColumnOrder;

/**
 * Class EloquentQuery
 * @package Logistio\Symmetry\Query;
 */
abstract class EloquentQuery extends Query
{
    /**
     * @var Builder
     */
    protected $queryBuilder;

    /**
     * @var array
     */
    protected $dateTimeColumns = [

    ];

    /**
     * @var array
     */
    protected $apiColumnCodes = [

    ];

    /**
     * @var array
     */
    protected $encodedIdColumns = [
    ];

    /**
     * @param \Closure|null $overrideCallback
     * @return Builder
     */
    protected abstract function buildQuery(\Closure $overrideCallback = null);

    /**
     * @param \Closure|null $overrideCallback
     * @param bool $fetchAll
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|Collection
     */
    public function execute(\Closure $overrideCallback = null, $fetchAll = false)
    {
        $this->buildQuery($overrideCallback);

        if ($fetchAll == true) {
            return $this->queryBuilder->get();
        }

        return $this->queryBuilder->paginate(
            $this->queryRequest->getPageLength()
        );
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

        $queryBuilder->where(function($query) use ($searchQuery, $searchableColumns) {

            foreach ($searchableColumns as $searchableColumn) {

                // The $searchableColumn variable corresponds to the `api_column_code` model
                // value for a query request.
                // We must get the actual data source column defined defined in the
                // $globalSearchableColumnsMap

                /** @var ApiColumnCodeTag $apiCodeTag */
                $apiCodeTag = array_get($this->apiColumnCodeTagsIdx, $searchableColumn);

                if (!$apiCodeTag) {
                    throw new ValidationException("The api column code `{$searchableColumn}` is invalid.");
                }

                $columnName = $apiCodeTag->getDatabaseColumn();

                $whereRaw = $this->createRawWhereClauseForGlobalSearch($columnName, $searchQuery);

                if (in_array($columnName, $this->encodedIdColumns) && $searchQuery) {
                    $query->orWhereRaw($whereRaw, [
                        $this->escapeQueryToken($searchQuery, false)
                    ]);
                }
                else {
                    $query->orWhereRaw($whereRaw, [
                        $this->escapeQueryToken($searchQuery)
                    ]);
                }
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

        /** @var Filter $filter */
        foreach ($this->queryRequest->getFilters() as $filter) {

            /** @var ApiColumnCodeTag $apiColumnTag */
            $apiColumnTag = array_get($this->apiColumnCodeTagsIdx, $filter->getApiColumnCode());

            if (is_null($apiColumnTag)) {
                throw new ValidationException("The api column code `{$apiColumnTag}` is invalid.");
            }

            $filter->attachWhereClauseToQueryBuilder($queryBuilder, $this->queryRequest);

        }
    }
}