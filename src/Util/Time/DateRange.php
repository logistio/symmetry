<?php

namespace Logistio\Symmetry\Util\Time;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Logistio\Symmetry\Util\ObjectUtil;

class DateRange implements Arrayable
{
    private static $PERIOD_YEARS = 'YEARS';
    private static $PERIOD_QUARTER = 'QUARTER';
    private static $PERIOD_MONTHS = 'MONTHS';
    private static $PERIOD_WEEKS = 'WEEKS';
    private static $PERIOD_DAYS = 'DAYS';

    /**
     * @var Carbon
     */
    private $dateFrom;

    /**
     * @var Carbon
     */
    private $dateTo;

    /**
     * DateRange constructor.
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     * @param bool $resetTime
     */
    public function __construct(Carbon $dateFrom, Carbon $dateTo, $resetTime = true)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        if ($resetTime) {
            $this->dateFrom->startOfDay();
            $this->dateTo->endOfDay();
        }
    }

    /**
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     * @return DateRange
     */
    public static function make(Carbon $dateFrom, Carbon $dateTo)
    {
        return new DateRange($dateFrom, $dateTo);
    }

    /**
     * @return Carbon
     */
    public function getDateFrom(): Carbon
    {
        return $this->dateFrom;
    }

    /**
     * @return Carbon
     */
    public function getDateTo(): Carbon
    {
        return $this->dateTo;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'date_from' => $this->dateFrom->toDateString(),
            'date_to' => $this->dateTo->toDateString()
        ];
    }

    /**
     * @return array
     */
    public function getYearDatesBetween()
    {
        return $this->getTimePeriodBetween(static::$PERIOD_YEARS);
    }

    public function getMonthDatesBetween()
    {
        return $this->getTimePeriodBetween(static::$PERIOD_MONTHS);
    }

    /**
     * @return array
     */
    public function getWeekDatesBetween()
    {
        return $this->getTimePeriodBetween(static::$PERIOD_WEEKS);
    }

    /**
     * @return array
     */
    public function getDatesBetween()
    {
        return $this->getTimePeriodBetween(static::$PERIOD_DAYS);
    }

    /**
     * @param $period
     * @return array
     */
    private function getTimePeriodBetween($period)
    {
        $dates = [];

        $cursor = $this->dateFrom->copy();

        while ($cursor->lte($this->dateTo)) {
            $dates[] = $cursor->copy();

            $this->incrementBy($cursor, $period);

            if ($period == static::$PERIOD_MONTHS && TimeUtil::isEndOfMonth($cursor)) {
                $cursor->addDay();
            }
        }

        return $dates;
    }

    /**
     * @return array
     */
    public function segmentToYearsTupleCollection()
    {
        return $this->segmentToTupleCollectionForPeriod(static::$PERIOD_YEARS);
    }

    /**
     * @return array
     */
    public function segmentToMonthsTupleCollection()
    {
        return $this->segmentToTupleCollectionForPeriod(static::$PERIOD_MONTHS);
    }

    /**
     * @return array
     */
    public function segmentToWeeksTupleCollection()
    {
        return $this->segmentToTupleCollectionForPeriod(static::$PERIOD_WEEKS);
    }

    /**
     * @return array
     */
    public function segmentToDaysTupleCollection()
    {
        return $this->segmentToTupleCollectionForPeriod(static::$PERIOD_DAYS);
    }

    /**
     * @return array
     */
    public function segmentToQuartersTupleCollection()
    {
        return $this->segmentToTupleCollectionForPeriod(static::$PERIOD_QUARTER);
    }

    /**
     * Given the start and end date this DateRange instance represents,
     * create a collection of tuples that are split by the $period
     * argument (eg. YEARS, MONTHS, WEEKS).
     * Eg. date_from: 2018-02-01, date_to:2018-04-30
     * - segmentToTupleCollectionForPeriod('MONTHS')
     * - Output:
     *
     * [0]
     *      [0]: 2018-02-01
     *      [1]: 2018-02-28
     * [1]
     *      [0]: 2018-03-01
     *      [1]: 2018-03-31
     * [2]
     *      [0]: 2018-04-01
     *      [1]: 2018-04-30
     *
     * @param $period
     * @return array
     */
    public function segmentToTupleCollectionForPeriod($period)
    {
        $tupleSegments = [];

        // If we are segmenting weeks, start from the start of the week
        // of the `dateFrom` and end at the last day of the week of
        // the `dateTo`.
        if ($period == static::$PERIOD_WEEKS) {
            $curDateFrom = $this->dateFrom->copy()->startOfWeek();
            $endDate = $this->dateTo->copy()->endOfWeek();
        }
        else if ($period == static::$PERIOD_QUARTER) {
            $startOfQuarter = $this->dateFrom->copy()->startOfQuarter();

            $curDateFrom = $this->dateFrom->copy();

            if ($this->dateFrom->gt($startOfQuarter)) {
                $curDateFrom = $this->dateFrom->copy()->endOfQuarter()->addDay();
            }

            $endOfQuarter = $this->dateTo->copy()->startOfQuarter();

            $endDate = $this->dateTo->copy();

            if ($this->dateTo->lt($endOfQuarter)) {
                $endDate = $this->dateTo->copy()->startOfQuarter()->subDay();
            }
        }
        else {
            $curDateFrom = $this->dateFrom->copy();
            $endDate = $this->dateTo;
        }

        while ($curDateFrom->lte($endDate)) {

            if ($period == static::$PERIOD_DAYS) {
                $tupleSegments[] = [
                    $curDateFrom->copy(),
                    $curDateFrom->copy()
                ];

                $curDateFrom->addDay();

                continue;
            }

            $segmentFromTo = [$curDateFrom->copy()];

            $curDateTo = $curDateFrom->copy();
            $this->incrementBy($curDateTo, $period);

            if ($curDateTo->gt($this->dateTo) && $period != static::$PERIOD_WEEKS) {
                // This tuple would finish later than the speicified dateTo.
                // We only include tuples in the output range that fill an entire period.
                break;
            }

            $segmentFromTo[] = $curDateTo->copy();

            $tupleSegments[] = $segmentFromTo;

            if ($period == static::$PERIOD_MONTHS || $period == static::$PERIOD_WEEKS || $period == static::$PERIOD_QUARTER|| $period == static::$PERIOD_YEARS) {
                /*
                 * [2019-02-06 PTS]
                 * According to the DateRangeTest, the expected behaviour
                 * is for Month ranges to start at the beginning of the month
                 * and end at the end of the month.
                 * Other range should produce half-open intervals, where consumers
                 * of those ranges would presumably treat the "from" date
                 * as included, and the "to" date as excluded.
                 *
                 * It seems that expected behaviour for month ranges is to
                 * create a range where the "from" date in each segment is the
                 *  first day in the calendar month, and the "to" date is the
                 * last day of that calendar month.
                 * This inconsistency is confusing, but we must investigate
                 * the impact of changing this behaviour before making any
                 * changes here.
                 */

                $nextDateFrom = $curDateTo->copy()->addDay();
            }
            else {
                $nextDateFrom = $curDateTo;
            }

            $curDateFrom = $nextDateFrom;
        }

        return $tupleSegments;
    }

    private function incrementBy(Carbon $carbonToIncrement, $period)
    {
        switch ($period) {
            case static::$PERIOD_YEARS: {
                $carbonToIncrement->addMonth(11)->endOfMonth();
                break;
            }
            case static::$PERIOD_QUARTER: {
                // Use the TimeUtil function to resolve
                TimeUtil::addMonth($carbonToIncrement);
                TimeUtil::addMonth($carbonToIncrement);
            }
            case static::$PERIOD_MONTHS: {
                // Use the TimeUtil function to resolve
                // overflowing dates.
                TimeUtil::addMonth($carbonToIncrement);
                break;
            }
            case static::$PERIOD_WEEKS: {
                $carbonToIncrement->addDay(6);
                break;
            }
            case static::$PERIOD_DAYS: {
                $carbonToIncrement->addDay();
                break;
            }
        }
    }

    /**
     * @return \Generator
     */
    function eachDay() {
        $cursor = $this->getDateFrom()->copy();

        while ($cursor->lte($this->getDateTo())) {

            yield $cursor;

            $cursor->addDay();
        }
    }
}