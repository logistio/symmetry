<?php

namespace Logistio\Symmetry\Util\Database;

use Illuminate\Database\Connection;

/**
 * Class PostgresUtil
 * @package Logistio\Symmetry\Util\Time\Database
 */
class PostgresUtil
{
    /**
     * @param Connection $connection
     * @return array
     */
    public static function getAllTableNames(Connection $connection)
    {
        return $connection->select("
            SELECT table_name
            FROM information_schema.tables
        ");
    }
}