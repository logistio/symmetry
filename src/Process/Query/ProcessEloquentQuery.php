<?php

namespace Logistio\Symmetry\Process\Query;

use Illuminate\Database\Eloquent\Builder;
use Logistio\Symmetry\Process\Process;
use Logistio\Symmetry\Query\EloquentQuery;

/**
 * Class ProcessEloquentQuery
 * @package Logistio\Symmetry\Process\Query
 */
class ProcessEloquentQuery extends EloquentQuery
{
    /**
     * @var ProcessQueryRequest
     */
    protected $queryRequest;

    /**
     * ProcessEloquentQuery constructor.
     * @param ProcessQueryRequest $queryRequest
     */
    public function __construct(ProcessQueryRequest $queryRequest)
    {
        parent::__construct($queryRequest);
    }

    /**
     * @param \Closure|null $overrideCallback
     * @return Builder|void
     * @throws \App\Exceptions\ValidationException
     */
    protected function buildQuery(\Closure $overrideCallback = null)
    {
        $builder = Process::query()
            ->join("process_state", "process_state.id", "=", "process.process_state_id");

        $this->setFilters($builder);
        $this->setGlobalSearchQuery($builder);
        $this->setColumnOrdering($builder);

        if ($overrideCallback) {
            $overrideCallback($builder);
        }

        $this->queryBuilder = $builder;
    }
}