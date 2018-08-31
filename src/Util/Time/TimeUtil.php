<?php

namespace Logistio\Symmetry\Util\Time;

use Carbon\Carbon;

class TimeUtil
{
    const TIMESTAMP_FORMAT = 'Y-m-d H:i:s';
    const DATE_FORMAT = 'Y-m-d';

    /**
     * @var null|Carbon
     */
    private static $overriddenNow = null;

    /**
     * Override the 'NOW' time.
     * Used for testing.
     *
     * @param Carbon $fakeNow
     * @return void
     */
    public static function overrideNow(Carbon $fakeNow)
    {
        self::$overriddenNow = $fakeNow;
    }

    /**
     * Remove the 'NOW' override.
     *
     * @return void
     */
    public static function removeNowOverride()
    {
        self::$overriddenNow = null;
    }

    /**
     * A wrapper for Carbon's now static method.
     *
     * @return Carbon|null
     */
    public static function now()
    {
        if (is_null(self::$overriddenNow)) {
            return new Carbon;
        } else {
            return self::$overriddenNow->copy();
        }
    }

    public static function nowAsTimestamp()
    {
        return self::dateTimeToApiTimestamp(self::now());
    }

    /**
     * @param Carbon $dateTime
     *
     * @return string
     *      UTC timestamp formatted for transmitting via the API.
     *      Format: "YYYY-MM-DD HH:mm:ss", e.g. "2018-01-30 18:43:09".
     */
    public static function dateTimeToApiTimestamp($dateTime)
    {
        return $dateTime->toDateTimeString();
    }

    /**
     * A wrapper for Carbon's today static method.
     *
     * @return Carbon
     */
    public static function today()
    {
        return Carbon::today();
    }

    /**
     * Get the Database Timezone
     *
     * @return string
     */
    public static function getDBTimezone()
    {
        return 'UTC';
    }

    /**
     * @param string $dbDateString
     *      Date string in 'Y-m-d' format.
     * @return Carbon
     *      UTC Carbon representation of the given date with the time set to midnight.
     */
    public static function dbDateToCarbonDate($dbDateString)
    {
        return Carbon::createFromFormat('Y-m-d', $dbDateString, self::getDBTimezone())
            ->setTime(0, 0, 0);
    }

    /**
     * @param $dbIntegerDate
     * @return false|static
     */
    public static function dbIntegerDateToCarbonDate($dbIntegerDate)
    {
        return Carbon::createFromFormat('Ymd', $dbIntegerDate, self::getDBTimezone())
            ->setTime(0, 0, 0);
    }

    /**
     * @param $dbIntegerDateTime
     * @return static
     */
    public static function dbIntegerDateTimeToCarbon($dbIntegerDateTime)
    {
        return Carbon::createFromFormat('Ymd His', $dbIntegerDateTime, self::getDBTimezone());
    }

    /**
     * @param $dbDateTimeString
     * @return Carbon
     *      UTC Carbon representation of the given date-time.
     */
    public static function dbDateTimeToCarbon($dbDateTimeString)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $dbDateTimeString, self::getDBTimezone());
    }

    /**
     *
     * @param $paramDate
     * @return false|static
     * @throws \Exception
     */
    public static function paramDateToCarbon($paramDate)
    {
        try {
            return Carbon::createFromFormat('Y-m-d', $paramDate, self::getDBTimezone())
                ->setTime(0, 0, 0);

        } catch (\Exception $e) {
            throw new \Exception("The date must be in YYYY-MM-DD format (e.g. 1999-12-31). Given: " . $paramDate);
        }
    }

    /**
     * SquareRoute Applications expect an external Date Time input to
     * be in then ISO8601 format - (YYYY-MM-DD HH:MM:SS in UTC).
     * The client is responsible for the datetime
     * format and the implied UTC timezone.
     *
     * @param $datetime
     * @throws \Exception
     * @return Carbon
     */
    public static function paramDateTimeToCarbon($datetime)
    {
        try {
            return Carbon::createFromFormat('Y-m-d H:i:s', $datetime, self::getDBTimezone());
        } catch (\Exception $e) {
            throw new \Exception("The datetime must be in YYYY-MM-DD HH:mm:ss format (e.g. `1999-12-31 18:30:45`). Received: " . self::paramToString($datetime));
        }
    }

    private static function paramToString($paramValue)
    {
        if (is_object($paramValue) || is_array($paramValue)) {
            return json_encode($paramValue);
        } else {
            return strval($paramValue);
        }

    }

    public static function apiDateTimeToCarbon($timestamp)
    {
        return self::paramDateTimeToCarbon($timestamp);
    }

    /**
     * @param Carbon $start
     * @param Carbon $end
     * @return bool
     */
    public static function areSequential(Carbon $start, Carbon $end)
    {
        return $start->lte($end);
    }

    /**
     * @param Carbon $dt
     * @return string
     */
    public static function fromCarbonToIntegerDate(Carbon $dt)
    {
        return $dt->format('Ymd');
    }

    /**
     * @param Carbon $dt
     * @return string
     */
    public static function fromCarbonToIntegerTime(Carbon $dt)
    {
        return intval($dt->format('His'));
    }

    public static function getYearsBetweenDateRange(DateRange $dateRange)
    {
        $dates = [];

        $dateFrom = $dateRange->getDateFrom();

        $dateTo = $dateRange->getDateTo();

        $cursor = $dateFrom->copy();

        $cursor->addYear();

        while($cursor->lte($dateTo)) {
            $dates[] = $cursor->copy();
        }

        return $dates;
    }

    /**
     * Return true if the two carbon objects
     * have the same date.
     *
     * @param Carbon $d1
     * @param Carbon $d2
     * @return bool
     */
    public static function areSameDate(Carbon $d1, Carbon $d2)
    {
        return $d1->toDateString() == $d2->toDateString();
    }

    /**
     * Return true if the $date is on the last
     * day of it's month.
     *
     * @param Carbon $date
     * @return bool
     */
    public static function isEndOfMonth(Carbon $date)
    {
        $copy = $date->copy();
        $copy->endOfMonth();

        return static::areSameDate($copy, $date);
    }

    /**
     * By default Carbon, or PHP's Datetime object rather, will
     * overflow the date when adding months. This function
     * allows the client to turn off the overflow
     * and resolve to the end of the next month.
     *
     * For example, by default adding
     * a month to `2018-05-31` will result to `2018-07-01` even
     * when the client expects the date to be `2018-06-30`.
     *
     *
     * @param Carbon $date
     * @param bool $overflow
     */
    public static function addMonth(Carbon $date, $overflow = false)
    {
        if ($overflow) {
            $date->addMonth();
        }

        $isEndOfMonth = static::isEndOfMonth($date);

        if (!$isEndOfMonth) {
            $date->addMonth();
            return;
        }

        $date->addDay();

        // Preserve the original time
        $originalTime = $date->toTimeString();

        $date->endOfMonth();

        $date->setTimeFromTimeString($originalTime);
    }
}