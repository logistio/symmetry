<?php


namespace Logistio\Symmetry\Query\Predicate;


abstract class MultipleQueriesPredicate extends PredicateModel
{
    /**
     * @var array
     */
    protected $queries;

    /**
     * MultipleQueriesPredicate constructor.
     * @param array $queries
     */
    public function __construct(array $queries)
    {
        $this->queries = $queries;
    }
}