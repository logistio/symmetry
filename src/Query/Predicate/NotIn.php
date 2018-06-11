<?php


namespace Logistio\Symmetry\Query\Predicate;

use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Query\Builder as DatabaseQueryBuilder;

use Logistio\Symmetry\Query\Macro\Util\DbStringSearchTrait;
use Logistio\Symmetry\Query\Request\QueryRequestInterface;
use Logistio\Symmetry\Query\Macro\ColumnCode\ApiColumnCodeTag;

class NotIn extends MultipleQueriesPredicate
{
    use DbStringSearchTrait;

    /**
     * @return string
     */
    function getSqlClause()
    {
        return 'NOT IN';
    }

    /**
     * @param EloquentQueryBuilder $builder
     * @param ApiColumnCodeTag $apiColumnCodeTag
     * @param QueryRequestInterface $queryRequest
     */
    public function attachWhereClauseToEloquentQueryBuilder(EloquentQueryBuilder $builder, ApiColumnCodeTag $apiColumnCodeTag, QueryRequestInterface $queryRequest)
    {
        $builder->whereRaw($this->getRawWhereClause($apiColumnCodeTag, $queryRequest));
    }

    /**
     * @param DatabaseQueryBuilder $builder
     * @param ApiColumnCodeTag $apiColumnCodeTag
     * @param QueryRequestInterface $queryRequest
     */
    public function attachWhereClauseToDatabaseQueryBuilder(DatabaseQueryBuilder $builder, ApiColumnCodeTag $apiColumnCodeTag, QueryRequestInterface $queryRequest)
    {
        $builder->whereRaw($this->getRawWhereClause($apiColumnCodeTag, $queryRequest));
    }


    /**
     * @param ApiColumnCodeTag $apiColumnCodeTag
     * @param QueryRequestInterface $queryRequest
     * @return string
     */
    private function getRawWhereClause(ApiColumnCodeTag $apiColumnCodeTag, QueryRequestInterface $queryRequest)
    {
        $queryTokens = "";

        foreach ($this->queries as $query) {
            $queryTokens .= "'{$query}',";
        }

        $queryTokens = substr($queryTokens, 0, -1);

        $queryTokens = "(" . $queryTokens . ")";

        $castString = $this->makeCastColumnString($apiColumnCodeTag, $queryRequest);

        return $castString . " NOT IN " . $queryTokens;
    }
}