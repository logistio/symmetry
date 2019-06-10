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

    /**
     * Returns the total number of child tables that
     * inherit from $table.
     *
     * Applies to Postgres V11.
     *
     * @param $table
     * @return mixed
     */
    public static function pgGetTotalChildTables($table)
    {
        $sql = "
            SELECT
                COUNT(child.*) AS total
            FROM pg_inherits
                     JOIN pg_class parent            ON pg_inherits.inhparent = parent.oid
                     JOIN pg_class child             ON pg_inherits.inhrelid   = child.oid
                     JOIN pg_namespace nmsp_parent   ON nmsp_parent.oid  = parent.relnamespace
                     JOIN pg_namespace nmsp_child    ON nmsp_child.oid   = child.relnamespace
            WHERE parent.relname=?;
        ";

        $count = \DB::select($sql, [
            $table
        ]);

        return $count[0]->{'total'};
    }
}