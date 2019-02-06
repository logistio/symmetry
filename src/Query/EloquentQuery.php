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
     * @deprecated
     * @var array
     */
    protected $dateTimeColumns = [

    ];

    /**
     * @deprecated
     * @var array
     */
    protected $apiColumnCodes = [

    ];

    /**
     * @deprecated
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
     * @return Builder
     */
    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }
}