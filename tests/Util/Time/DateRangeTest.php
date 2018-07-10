<?php


namespace Logistio\Symmetry\Test\Util\Time;

use Logistio\Symmetry\Test\TestCase;
use Logistio\Symmetry\Util\Time\DateRange;
use Logistio\Symmetry\Util\Time\TimeUtil;

class DateRangeTest extends TestCase
{
    /**
     * @test
     */
    public function testGetTimePeriodBetween()
    {
        // YEARS
        $dateRange = new DateRange(
            TimeUtil::now()->subYear(),
            TimeUtil::now()
        );

        $yearDates = $dateRange->getYearDatesBetween();

        $this->assertEquals(1, count($yearDates));

        $dateRange = new DateRange(
            TimeUtil::now()->subWeek(51),
            TimeUtil::now()
        );

        $yearDates = $dateRange->getYearDatesBetween();

        $this->assertEquals(0, count($yearDates));

        // MONTHS
        $dateRange = new DateRange(
            TimeUtil::now()->subMonth(5),
            TimeUtil::now()
        );

        $monthDates = $dateRange->getMonthDatesBetween();

        $this->assertEquals(5, count($monthDates));

        $dateRange = new DateRange(
            TimeUtil::now()->subWeek(3),
            TimeUtil::now()
        );

        $monthDates = $dateRange->getMonthDatesBetween();

        $this->assertEquals(0, count($monthDates));

        // WEEKS
        $dateRange = new DateRange(
            TimeUtil::now()->subWeeks(13),
            TimeUtil::now()
        );

        $weekDates = $dateRange->getWeekDatesBetween();

        $this->assertEquals(13, count($weekDates));

        $dateRange = new DateRange(
            TimeUtil::now()->subDay(6),
            TimeUtil::now()
        );

        $weekDates = $dateRange->getWeekDatesBetween();

        $this->assertEquals(0, count($weekDates));

        // DAYS

        $dateRange = new DateRange(
            TimeUtil::now()->subDay(45),
            TimeUtil::now()
        );

        $dates = $dateRange->getDatesBetween();

        $this->assertEquals(45, count($dates));

        $dateRange = new DateRange(
            TimeUtil::now()->subHour(6),
            TimeUtil::now()
        );

        $dates = $dateRange->getDatesBetween();

        $this->assertEquals(0, count($dates));
    }

}