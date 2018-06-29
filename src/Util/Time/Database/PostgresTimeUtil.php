<?php


namespace Logistio\Symmetry\Util\Time\Database;


class PostgresTimeUtil
{
    /**
     * Cast the column value to a Postgres Date.
     *
     * @param $columnName
     * @return string
     */
    public static function toDateCastFromIntegerColumn($columnName)
    {
        return "TO_DATE({$columnName}, 'YYYYMMDD')";
    }
}