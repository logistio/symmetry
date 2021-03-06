<?php


namespace Logistio\Symmetry\Query\Predicate;

use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Query\Builder as DatabaseQueryBuilder;

use Logistio\Symmetry\Query\Macro\ColumnCode\ApiColumnCodeTag;
use Logistio\Symmetry\Query\Request\QueryRequestInterface;
use Logistio\Symmetry\Query\Macro\Util\DbStringSearchTrait;

class Min extends SingleQueryPredicate
{
    use DbStringSearchTrait;

    /**
     * @return string
     */
    function getSqlClause()
    {
        return 'MIN';
    }

    /**
     * @param EloquentQueryBuilder $builder
     * @param ApiColumnCodeTag $apiColumnCodeTag
     * @param QueryRequestInterface $queryRequest
     */
    public function attachWhereClauseToEloquentQueryBuilder(EloquentQueryBuilder $builder, ApiColumnCodeTag $apiColumnCodeTag, QueryRequestInterface $queryRequest)
    {
        $builder->whereRaw($this->getRawWhereClause($apiColumnCodeTag, $queryRequest), [
            $this->query
        ]);
    }

    /**
     * @param DatabaseQueryBuilder $builder
     * @param ApiColumnCodeTag $apiColumnCodeTag
     * @param QueryRequestInterface $queryRequest
     */
    public function attachWhereClauseToDatabaseQueryBuilder(DatabaseQueryBuilder $builder, ApiColumnCodeTag $apiColumnCodeTag, QueryRequestInterface $queryRequest)
    {
        $builder->whereRaw($this->getRawWhereClause($apiColumnCodeTag, $queryRequest), [
            $this->query
        ]);
    }

    /**
     * @param ApiColumnCodeTag $apiColumnCodeTag
     * @param QueryRequestInterface $queryRequest
     * @return string
     */
    private function getRawWhereClause(ApiColumnCodeTag $apiColumnCodeTag, QueryRequestInterface $queryRequest)
    {
        $castString = $this->makeCastColumnString($apiColumnCodeTag, $queryRequest);

        return $castString . " >= ?";

    }
}