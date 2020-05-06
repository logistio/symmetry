<?php

namespace Logistio\Symmetry\Util\Database;

use Illuminate\Database\Connection;
use Illuminate\Database\PostgresConnection;

/**
 * Class PostgresUtil
 * @package Logistio\Symmetry\Util\Time\Database
 */
class PostgresUtil
{
    /**
     * @param Connection|PostgresConnection $connection
     * @return array
     */
    public static function getAllTableNames(Connection $connection)
    {
        return $connection->getDoctrineConnection()->getSchemaManager()->listTableNames();
    }
}