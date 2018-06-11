<?php


namespace Logistio\Symmetry\Query\Predicate;


abstract class SingleQueryPredicate extends PredicateModel
{
    /**
     * @var string|int|double
     */
    protected $query;

    /**
     * SingleQueryPredicate constructor.
     * @param float|int|string $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }
}