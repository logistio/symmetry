<?php


namespace Logistio\Symmetry\Query\Macro\Util;
use Logistio\Symmetry\Query\Macro\ColumnCode\ApiColumnCodeTag;
use Logistio\Symmetry\Query\Request\QueryRequestInterface;

/**
 */
trait DbStringSearchTrait
{
    /**
     * Before searching for a DB value using
     * the `LIKE` statement, this function
     * will make sure the sure that
     * `%` characters in the search
     * string are escaped.
     *
     * @param $rawValue
     * @param $withPercentages
     * @return string
     */
    protected function escapeQueryToken($rawValue, $withPercentages = true)
    {
        /*
         * Convert the query to lower case so that the search
         * will be performed in case INSENSITIVE mode.
         */
        $query = strtolower(
            trim($rawValue)
        );

        $escapedQuery = str_replace('%', '\\%', $query);

        $queryToken = $escapedQuery;

        if ($withPercentages) {
            $queryToken = "%${escapedQuery}%";
        }

        return $queryToken;
    }

    /**
     * Create the raw SQL to allow a `LIKE` comparison on a timestamp column.
     *
     * @param $columnName
     * @param $timezone
     * @return string
     */
    protected function makeDatetimeRawLikeClause($columnName, $timezone)
    {
        return "{$this->makeDatetimeColumnCastString($columnName, $timezone)} LIKE ?";
    }

    /**
     * Create the raw SQL to allow a `NOT LIKE` comparison on a timestamp column.
     *
     * @param $columnName
     * @param $timezone
     * @return string
     */
    protected function makeDatetimeRawNotLikeClause($columnName, $timezone)
    {
        return "{$this->makeDatetimeColumnCastString($columnName, $timezone)} NOT LIKE ?";
    }

    /**
     * @param $columnName
     * @return string
     */
    protected function makeDefaultRawLikeClause($columnName)
    {
        return "{$this->makeDefaultCastString($columnName)} LIKE ?";
    }

    /**
     * @param $columnName
     * @return string
     */
    protected function makeDefaultRawNotLikeClause($columnName)
    {
        return "{$this->makeDefaultCastString($columnName)} NOT LIKE ?";
    }

    /**
     * Get the SQL snippet that will cast a timestamp column to a
     * character.
     *
     * @param $columnName
     * @param $timezone
     * @return string
     */
    protected function makeDatetimeColumnCastString($columnName, $timezone)
    {
        return "TO_CHAR({$columnName} AT time zone '{$timezone}', 'YYYY-MM-DD HH24:MI:SS') ";
    }

    /**
     * @param $columnName
     * @return string
     */
    protected function makeDefaultCastString($columnName)
    {
        return "LOWER( CAST( {$columnName} as TEXT))";
    }

    /**
     * @param ApiColumnCodeTag $apiColumnCodeTag
     * @param QueryRequestInterface $queryRequest
     * @return string
     */
    protected function makeCastColumnString(ApiColumnCodeTag $apiColumnCodeTag, QueryRequestInterface $queryRequest)
    {
        if ($apiColumnCodeTag->isTypeDatetime()) {
            return $this->makeDatetimeColumnCastString($apiColumnCodeTag->getDatabaseColumn(), $queryRequest->getClientTimezone());
        }

        if ($apiColumnCodeTag->isTypeNumber()) {
            return "{$apiColumnCodeTag->getDatabaseColumn()}";
        }

        return $this->makeDefaultCastString($apiColumnCodeTag->getDatabaseColumn());
    }
}