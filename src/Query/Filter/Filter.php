<?php


namespace Logistio\Symmetry\Query\Filter;

use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Query\Builder as DatabaseQueryBuilder;

use Logistio\Symmetry\Query\Macro\ColumnCode\ApiColumnCodeTag;
use Logistio\Symmetry\Query\Predicate\PredicateModel;
use Logistio\Symmetry\Query\Request\QueryRequestInterface;

class Filter
{
    /**
     * @var string
     */
    protected $columnName;

    /**
     * @var string
     */
    protected $apiColumnCode;

    /** @var ApiColumnCodeTag */
    protected $apiColumnCodeTag;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var PredicateModel
     */
    protected $predicateModel;

    /**
     * @return string
     */
    public function getColumnName()
    {
        return $this->columnName;
    }

    /**
     * @param string $columnName
     */
    public function setColumnName($columnName)
    {
        $this->columnName = $columnName;
    }

    /**
     * @return string
     */
    public function getApiColumnCode()
    {
        return $this->apiColumnCode;
    }

    /**
     * @param string $apiColumnCode
     */
    public function setApiColumnCode($apiColumnCode)
    {
        $this->apiColumnCode = $apiColumnCode;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return PredicateModel
     */
    public function getPredicateModel()
    {
        return $this->predicateModel;
    }

    /**
     * @param PredicateModel $predicateModel
     */
    public function setPredicateModel(PredicateModel $predicateModel)
    {
        $this->predicateModel = $predicateModel;
    }

    /**
     * @return ApiColumnCodeTag
     */
    public function getApiColumnCodeTag(): ApiColumnCodeTag
    {
        return $this->apiColumnCodeTag;
    }

    /**
     * @param ApiColumnCodeTag $apiColumnCodeTag
     */
    public function setApiColumnCodeTag(ApiColumnCodeTag $apiColumnCodeTag): void
    {
        $this->apiColumnCodeTag = $apiColumnCodeTag;
    }


    /**
     * @param $builder
     * @param QueryRequestInterface $queryRequest
     */
    public function attachWhereClauseToQueryBuilder($builder, QueryRequestInterface $queryRequest)
    {
        if ($builder instanceof EloquentQueryBuilder) {
            $this->predicateModel->attachWhereClauseToEloquentQueryBuilder($builder, $this->apiColumnCodeTag, $queryRequest);
        }
        else if ($builder instanceof DatabaseQueryBuilder) {
            $this->predicateModel->attachWhereClauseToDatabaseQueryBuilder($builder, $this->apiColumnCodeTag, $queryRequest);
        }
    }
}