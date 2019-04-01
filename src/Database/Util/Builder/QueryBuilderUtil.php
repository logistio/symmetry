<?php

namespace Logistio\Symmetry\Database\Util\Builder;

use Illuminate\Database\Query\Builder;

class QueryBuilderUtil
{
    /**
     * @param Builder $builder
     * @return string
     */
    public static function toSql(Builder $builder)
    {
        return str_replace_array('?', $builder->getBindings(), $builder->toSql());
    }
}