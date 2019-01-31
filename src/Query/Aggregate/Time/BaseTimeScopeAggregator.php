<?php

namespace Logistio\Symmetry\Process\Query\Aggregate\Time;

use Logistio\Symmetry\Exception\ValidationException;

abstract class BaseTimeScopeAggregator
{
    const SCOPE_HOUR = 'HOUR';
    const SCOPE_DAY = 'DAY';
    const SCOPE_WEEK = 'WEEK';
    const SCOPE_MONTH = 'MONTH';
    const SCOPE_QUARTER = 'QUARTER';
    const SCOPE_YEAR = 'YEAR';
    const MULTI_PERIOD = 'MULTI_PERIOD';
    const SCOPE_ALL = 'ALL';

    protected $selectStatementScopeAliasesMap = [
        self::SCOPE_DAY => 'day',
        self::SCOPE_WEEK => 'week_number',
        self::SCOPE_MONTH . "_NUMBER" => 'month_number',
        self::SCOPE_MONTH . "_NAME" => 'month_name',
        self::SCOPE_QUARTER => 'quarter',
        self::SCOPE_YEAR => 'year',
    ];

    /**
     * @var array
     */
    protected $scopeOrderByRank = [
        self::SCOPE_HOUR,
        self::SCOPE_DAY,
        self::SCOPE_WEEK,
        self::SCOPE_MONTH,
        self::SCOPE_QUARTER,
        self::SCOPE_YEAR,
    ];

    /**
     * @var string
     */
    protected $scope;

    public function __construct(string $scope)
    {
        $this->scope = $scope;

        $this->validateScope();
    }

    /**
     * @return bool
     */
    public function isScopeAll()
    {
        return static::SCOPE_ALL == $this->scope;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return array
     */
    public function getScopes()
    {
        return static::getSupportedScopes();
    }

    /**
     * @return array
     */
    public static function getSupportedScopes()
    {
        return [
            static::SCOPE_HOUR,
            static::SCOPE_DAY,
            static::SCOPE_WEEK,
            static::SCOPE_MONTH,
            static::SCOPE_QUARTER,
            static::SCOPE_YEAR,
            static::SCOPE_ALL
        ];
    }

    /**
     * @param $columnName
     * @return string
     */
    public function getExtractYearFromColumnSql($columnName)
    {
        return "EXTRACT(YEAR FROM {$columnName})";
    }

    public function getExtractQuarterFromColumnSql($columnName)
    {
        return "EXTRACT(QUARTER FROM {$columnName})";
    }

    /**
     * Extract the month number from the column.
     *
     * @param $columnName
     * @return string
     */
    public function getExtractMonthNumberFromColumnSql($columnName)
    {
        return "EXTRACT(MONTH FROM {$columnName})";
    }

    /**
     * Extract the month name from the column.
     *
     * @param $columnName
     * @return string
     */
    public function getExtractMonthNameFromColumnSql($columnName)
    {
        return "TO_CHAR({$columnName}, 'Month')";
    }

    /**
     * Extract the week number from the column.
     *
     * @param $columnName
     * @return string
     */
    public function getExtractWeekNumberFromColumnSql($columnName)
    {
        return "EXTRACT(WEEK FROM {$columnName})";
    }

    public function getAggregationPeriodForYearScope($column)
    {
        return "TO_CHAR(DATE_TRUNC('year', {$column}), 'YYYY-MM-DD')";
    }

    /**
     * @param $column
     * @return string
     */
    public function getAggregationPeriodForQuarterScope($column)
    {
        return "TO_CHAR(DATE_TRUNC('quarter', {$column}), 'YYYY-MM-DD')";
    }

    /**
     * @param $column
     * @return string
     */
    public function getAggregationPeriodForMonthScope($column)
    {
        return "TO_CHAR(DATE_TRUNC('month', {$column}), 'YYYY-MM-DD')";
    }

    /**
     * @param $column
     * @return string
     */
    public function getAggregationPeriodForWeekScope($column)
    {
        return "TO_CHAR(DATE_TRUNC('week', {$column}), 'YYYY-MM-DD')";
    }

    /**
     * @param $column
     * @return string
     */
    public function getAggregationPeriodForDayScope($column)
    {
        return "{$column}";
    }

    public function getAggregationPeriod($column)
    {
        switch ($this->scope) {
            case static::SCOPE_YEAR: {
                return $this->getAggregationPeriodForYearScope($column);
            }
            case static::SCOPE_QUARTER: {
                return $this->getAggregationPeriodForQuarterScope($column);
            }
            case static::SCOPE_MONTH: {
                return $this->getAggregationPeriodForMonthScope($column);
            }
            case static::SCOPE_WEEK: {
                return $this->getAggregationPeriodForWeekScope($column);
            }
            case static::SCOPE_DAY: {
                return $this->getAggregationPeriodForDayScope($column);
            }
            default: {
                throw new \InvalidArgumentException("No group by sql defined for scope `{$this->scope}`.");
            }
        }
    }

    /**
     * @param $dateColumn
     * @return string
     */
    public function getGroupByYearSql($dateColumn)
    {
        return $this->getExtractYearFromColumnSql($dateColumn);
    }

    /**
     * @param $dateColumn
     * @return string
     */
    public function getGroupByQuarterSql($dateColumn)
    {
        return "
            {$this->getExtractYearFromColumnSql($dateColumn)},
            {$this->getExtractQuarterFromColumnSql($dateColumn)}
        ";
    }

    /**
     * @param $dateColumn
     * @return string
     */
    public function getGroupByMonthSql($dateColumn)
    {
        return "
            {$this->getExtractYearFromColumnSql($dateColumn)},
            {$this->getExtractQuarterFromColumnSql($dateColumn)},
            {$this->getExtractMonthNumberFromColumnSql($dateColumn)},
            {$this->getExtractMonthNameFromColumnSql($dateColumn)}
        ";
    }

    /**
     * @param $dateColumn
     * @return string
     */
    public function getGroupByWeekSql($dateColumn)
    {
        return "
            {$this->getExtractYearFromColumnSql($dateColumn)},
            -- {$this->getExtractQuarterFromColumnSql($dateColumn)},
            {$this->getExtractWeekNumberFromColumnSql($dateColumn)}
        ";
    }

    /**
     * @param $dateColumn
     * @return string
     */
    public function getGroupByDaySql($dateColumn)
    {
        return "
            {$this->getExtractYearFromColumnSql($dateColumn)},
            {$this->getExtractQuarterFromColumnSql($dateColumn)},
            {$this->getExtractMonthNumberFromColumnSql($dateColumn)},
            {$this->getExtractMonthNameFromColumnSql($dateColumn)},
            {$this->getExtractWeekNumberFromColumnSql($dateColumn)},
            {$dateColumn}
        ";
    }

    /**
     * @param $column
     * @return string
     */
    public function getGroupByScopeSqlForColumn($column)
    {
        /*
         * Note that the inheriting class must be responsible for dealing with
         * SCOPE_ALL.
         */

        switch ($this->scope) {
            case static::SCOPE_YEAR: {
                return $this->getGroupByYearSql($column) . ", " . $this->getAggregationPeriod($column);
            }
            case static::SCOPE_QUARTER: {
                return $this->getGroupByQuarterSql($column) . ", " . $this->getAggregationPeriodForQuarterScope($column);
            }
            case static::SCOPE_MONTH: {
                return $this->getGroupByMonthSql($column) . ", " . $this->getAggregationPeriodForMonthScope($column);
            }
            case static::SCOPE_WEEK: {
                return $this->getGroupByWeekSql($column) . ", " . $this->getAggregationPeriodForWeekScope($column);
            }
            case static::SCOPE_DAY: {
                return $this->getGroupByDaySql($column) . ", " . $this->getAggregationPeriodForDayScope($column);
            }
            default: {
                throw new \InvalidArgumentException("No group by sql defined for scope `{$this->scope}`.");
            }
        }
    }

    public function getTimeScopesSelectStatementForColumn($column)
    {
        $yearAlias = $this->selectStatementScopeAliasesMap[static::SCOPE_YEAR];
        $quarterAlias = $this->selectStatementScopeAliasesMap[static::SCOPE_QUARTER];
        $monthNumberAlias = $this->selectStatementScopeAliasesMap[static::SCOPE_MONTH . "_NUMBER"];
        $monthNameAlias = $this->selectStatementScopeAliasesMap[static::SCOPE_MONTH . "_NAME"];
        $weekNumberAlias = $this->selectStatementScopeAliasesMap[static::SCOPE_WEEK];

        switch ($this->scope) {
            case static::SCOPE_YEAR: {
                return "
                    {$this->getExtractYearFromColumnSql($column)} AS {$yearAlias}
                ";
            }
            case static::SCOPE_QUARTER: {
                return "
                    {$this->getExtractYearFromColumnSql($column)} AS {$yearAlias},
                    {$this->getExtractQuarterFromColumnSql($column)} AS {$quarterAlias}
                ";
            }
            case static::SCOPE_MONTH: {
                return "
                    {$this->getExtractYearFromColumnSql($column)} AS {$yearAlias},
                    {$this->getExtractQuarterFromColumnSql($column)} AS {$quarterAlias},
                    {$this->getExtractMonthNumberFromColumnSql($column)} AS {$monthNumberAlias},
                    TRIM({$this->getExtractMonthNameFromColumnSql($column)}) AS {$monthNameAlias}
                ";
            }
            case static::SCOPE_WEEK: {
                return "
                    {$this->getExtractYearFromColumnSql($column)} AS {$yearAlias},
                    {$this->getExtractWeekNumberFromColumnSql($column)} AS {$weekNumberAlias}
                ";
            }
            case static::SCOPE_DAY: {
                return "
                    {$this->getExtractYearFromColumnSql($column)} AS {$yearAlias},
                    {$this->getExtractQuarterFromColumnSql($column)} AS {$quarterAlias},
                    {$this->getExtractMonthNumberFromColumnSql($column)} AS {$monthNumberAlias},
                    TRIM({$this->getExtractMonthNameFromColumnSql($column)}) AS {$monthNameAlias},
                    {$this->getExtractWeekNumberFromColumnSql($column)} AS {$weekNumberAlias},
                    ${column} AS date
                ";
            }
            default: {
                throw new \InvalidArgumentException("No group by sql defined for scope `{$this->scope}`.");
            }
        }
    }

    private function validateScope()
    {
        if (!in_array($this->scope, $this->getScopes())) {
            throw new ValidationException("The scope `{$this->scope}` is invalid.");
        }
    }

    /**
     * If your query requires a window function to be partitioned by the date,
     * use this methods to get the extract functions depending on your
     * time scope. Note how we order the extract functions by the most granular scopes for
     * lower level scopes.
     *
     * @param $column
     * @return string
     */
    protected function getTimeScopeExtractFunctionsForWindowPartition($column)
    {
        switch ($this->scope) {
            case static::SCOPE_YEAR: {
                return "
                    {$this->getExtractYearFromColumnSql($column)}
                ";
            }
            case static::SCOPE_QUARTER: {
                return "
                    {$this->getExtractQuarterFromColumnSql($column)},
                    {$this->getExtractYearFromColumnSql($column)}
                ";
            }
            case static::SCOPE_MONTH: {
                return "
                    {$this->getExtractMonthNumberFromColumnSql($column)},
                    {$this->getExtractQuarterFromColumnSql($column)},
                    {$this->getExtractYearFromColumnSql($column)}
                ";
            }
            case static::SCOPE_WEEK: {
                return "
                    {$this->getExtractWeekNumberFromColumnSql($column)},
                    {$this->getExtractYearFromColumnSql($column)}
                ";
            }
            case static::SCOPE_DAY: {
                return "{$column}";
            }
            default: {
                throw new \InvalidArgumentException("No group by sql defined for scope `{$this->scope}`.");
            }
        }
    }

    /**
     * @param $columnScope
     * @return bool
     */
    public function isOrderByAllowed($columnScope)
    {
        $currentScopeRank = array_search($this->scope, $this->scopeOrderByRank);

        $columnScopeRank = array_search($columnScope, $this->scopeOrderByRank);

        return $columnScopeRank >= $currentScopeRank;
    }

}