<?php

namespace Logistio\Symmetry\Query;

use Logistio\Symmetry\Query\Macro\Util\QueryBuildingUtilities;
use Logistio\Symmetry\Query\Request\QueryRequest;
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
    use QueryBuildingUtilities;

    /**
     * The QueryRequest represents all the options required
     * for executing the Query.
     *
     * @var QueryRequestInterface|QueryRequest
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
     * @param QueryRequestInterface $queryRequest
     */
    public function __construct($queryRequest)
    {
        $this->queryRequest = $queryRequest;

        $this->apiColumnCodeTags = $queryRequest->getApiColumnCodeTags();

        $this->apiColumnCodeTagsIdx = $queryRequest->getApiColumnCodeTagsIdx();
    }


}