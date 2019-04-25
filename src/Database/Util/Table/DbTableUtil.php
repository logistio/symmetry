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

    /**
     * @param string $column
     * @param string $table
     * @return bool
     */
    public static function doesColumnExistOnTable(string $column, string $table): bool
    {
        return Schema::hasColumn($table, $column);
    }

    /**
     * @param $indexName
     * @param $table
     * @return bool
     */
    public static function doesIndexExistOnTable($indexName, $table): bool
    {
        $manager = \Schema::getConnection()->getDoctrineSchemaManager();

        $indexesFound = $manager->listTableIndexes($table);

        return array_key_exists($indexName, $indexesFound);
    }
}