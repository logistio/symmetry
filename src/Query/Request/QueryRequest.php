<?php

namespace Logistio\Symmetry\Query\Request;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Logistio\Symmetry\Query\Macro\ColumnCode\ApiColumnCodeTag;
use Logistio\Symmetry\Query\Request\Order\ColumnOrder;

/**
 * Class QueryRequest
 */
class QueryRequest implements QueryRequestInterface
{
    /**
     * @var int
     */
    protected $pageLength;

    /**
     * @var ColumnOrder[]
     */
    protected $columnOrdering;

    /**
     * @var array - A list of columns names in the result data set
     * which can be searched.
     */
    protected $searchableColumns;

    /**
     * @var string - The search query to apply to the list
     * of `$searchableColumns`.
     */
    protected $globalSearchQuery;

    /**
     * @var string - The timezone of the client that
     * submitted the QueryRequest.
     */
    protected $clientTimezone;

    /**
     * @var array
     */
    protected $filters;

    /**
     * @var Collection|ApiColumnCodeTag[]
     */
    protected $apiColumnCodeTags;

    /**
     * @var array
     */
    protected $apiColumnCodeTagsIdx;

    /**
     * @return Carbon
     */
    public function getDateTo(): Carbon
    {
        return $this->dateTo;
    }

    /**
     * @param Carbon $dateTo
     */
    public function setDateTo(Carbon $dateTo)
    {
        $this->dateTo = $dateTo;
    }

    /**
     * @return int
     */
    public function getPageLength()
    {
        return $this->pageLength;
    }

    /**
     * @param int $pageLength
     */
    public function setPageLength($pageLength)
    {
        $this->pageLength = $pageLength;
    }

    /**
     * @return ColumnOrder[]
     */
    public function getColumnOrdering()
    {
        return $this->columnOrdering;
    }

    /**
     * @param ColumnOrder[] $columnOrdering
     */
    public function setColumnOrdering($columnOrdering)
    {
        $this->columnOrdering = $columnOrdering;
    }

    /**
     * @return array
     */
    public function getSearchableColumns()
    {
        return $this->searchableColumns;
    }

    /**
     * @param array $searchableColumns
     */
    public function setSearchableColumns($searchableColumns)
    {
        $this->searchableColumns = $searchableColumns;
    }

    /**
     * @return string
     */
    public function getGlobalSearchQuery()
    {
        return $this->globalSearchQuery;
    }

    /**
     * @param string $globalSearchQuery
     */
    public function setGlobalSearchQuery($globalSearchQuery)
    {
        $this->globalSearchQuery = $globalSearchQuery;
    }

    /**
     * @return string
     */
    public function getClientTimezone()
    {
        return $this->clientTimezone;
    }

    /**
     * @param string $clientTimezone
     */
    public function setClientTimezone($clientTimezone)
    {
        $this->clientTimezone = $clientTimezone;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @return Collection|ApiColumnCodeTag[]
     */
    public function getApiColumnCodeTags()
    {
        return $this->apiColumnCodeTags;
    }

    /**
     * @return array
     */
    public function getApiColumnCodeTagsIdx()
    {
        return $this->apiColumnCodeTagsIdx;
    }

    /**
     * @param Collection|ApiColumnCodeTag[] $apiColumnCodeTags
     */
    public function setApiColumnCodeTags($apiColumnCodeTags)
    {
        $this->apiColumnCodeTags = $apiColumnCodeTags;

        $this->apiColumnCodeTagsIdx = $this->apiColumnCodeTags->keyBy(function($tag) {
            return $tag->getCode();
        });
    }

}