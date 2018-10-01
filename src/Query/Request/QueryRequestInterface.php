<?php

namespace Logistio\Symmetry\Query\Request;

/**
 * Interface QueryRequestInterface
 *
 * Interface to a QueryRequest.
 *
 * A QueryRequest contains the configuration options
 * for a Query.
 */
interface QueryRequestInterface
{
    /**
     * @return int
     */
    public function getPageLength();

    /**
     * @param int $pageLength
     */
    public function setPageLength($pageLength);

    /**
     * @return array
     */
    public function getColumnOrdering();

    /**
     * @param array $columnOrdering
     */
    public function setColumnOrdering($columnOrdering);

    /**
     * @return array
     */
    public function getSearchableColumns();

    /**
     * @param array $searchableColumns
     */
    public function setSearchableColumns($searchableColumns);

    /**
     * @return string
     */
    public function getGlobalSearchQuery();

    /**
     * @param string $globalSearchQuery
     */
    public function setGlobalSearchQuery($globalSearchQuery);

    /**
     * @return string
     */
    public function getClientTimezone();

    /**
     * @param string $clientTimezone
     */
    public function setClientTimezone($clientTimezone);

    /**
     * @param $filters
     * @return mixed
     */
    public function setFilters($filters);

    /**
     * @return array
     */
    public function getFilters();

    /**
     * @return bool
     */
    public function isDateRangeAvailable();

}