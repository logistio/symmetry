<?php

namespace Logistio\Symmetry\Database\Util\Builder;

use Illuminate\Support\Str;
use Illuminate\Database\Query\Builder;

class QueryBuilderUtil
{
    /**
     * @param Builder $builder
     * @return string
     */
    public static function toSql(Builder $builder)
    {
        return Str::replaceArray('?', $builder->getBindings(), $builder->toSql());
    }
}