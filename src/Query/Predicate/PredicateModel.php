<?php


namespace Logistio\Symmetry\Query\Predicate;


use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Query\Builder as DatabaseQueryBuilder;

use Logistio\Symmetry\Query\Macro\ColumnCode\ApiColumnCodeTag;
use Logistio\Symmetry\Query\Request\QueryRequestInterface;

abstract class PredicateModel
{
    const TYPE_EQUALS = 'EQUALS';
    const TYPE_NOT_EQUALS = 'NOT_EQUALS';
    const TYPE_STRICT_EQUALS = 'STRICT_EQUALS';
    const TYPE_STRICT_NOT_EQUALS = 'STRICT_NOT_EQUALS';
    const TYPE_MIN = 'MIN';
    const TYPE_MAX = 'MAX';
    const TYPE_RANGE = 'RANGE';
    const TYPE_IN = 'IN';
    const TYPE_NOT_IN = 'NOT_IN';
    const TYPE_LESS_THAN = 'LESS_THAN';
    const TYPE_LESS_THAN_OR_EQUAL_TO = 'LESS_THAN_OR_EQUAL_TO';
    const TYPE_GREATER_THAN = 'GREATER_THAN';
    const TYPE_GREATER_THAN_OR_EQUAL_TO = 'GREATER_THAN_OR_EQUAL_TO';


    /**
     * @return string
     */
    abstract function getSqlClause();


    abstract function attachWhereClauseToEloquentQueryBuilder(EloquentQueryBuilder $builder, ApiColumnCodeTag $apiColumnCodeTag, QueryRequestInterface $queryRequest);

    abstract function attachWhereClauseToDatabaseQueryBuilder(DatabaseQueryBuilder $builder, ApiColumnCodeTag $apiColumnCodeTag, QueryRequestInterface $queryRequest);

    /**
     * @param $type
     * @return bool
     */
    public static function isTypeValid($type)
    {
        return in_array($type, static::getTypes());
    }

    public static function getTypes()
    {
        return [
            static::TYPE_EQUALS,
            static::TYPE_NOT_EQUALS,
            static::TYPE_STRICT_EQUALS,
            static::TYPE_STRICT_NOT_EQUALS,
            static::TYPE_MIN,
            static::TYPE_MAX,
            static::TYPE_RANGE,
            static::TYPE_IN,
            static::TYPE_NOT_IN,
            static::TYPE_LESS_THAN,
            static::TYPE_LESS_THAN_OR_EQUAL_TO,
            static::TYPE_GREATER_THAN,
            static::TYPE_GREATER_THAN_OR_EQUAL_TO,
        ];
    }

    /**
     * @param $type
     * @return bool
     */
    public static function isTypeMultipleQueryPredicate($type)
    {
        return in_array($type, [
            static::TYPE_RANGE,
            static::TYPE_IN,
            static::TYPE_NOT_IN,
        ]);
    }
}