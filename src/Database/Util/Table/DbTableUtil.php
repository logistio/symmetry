<?php

namespace Logistio\Symmetry\Database\Util\Table;

use Illuminate\Support\Facades\Schema;

/**
 * Class DbTableUtil
 * @package Logistio\Symmetry\Database\Util\Table
 */
class DbTableUtil
{
    /**
     * @param string $table
     * @return bool
     */
    public static function doesTableExist(string $table): bool
    {
        return Schema::hasTable($table);
    }
}