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
    public function test_segment_to_tuple_collection_for_period()
    {
        $dateRange = new DateRange(
            TimeUtil::paramDateToCarbon('2018-02-01'),
            TimeUtil::paramDateToCarbon('2018-05-01')
        );

        $segments = collect($dateRange->segmentToMonthsTupleCollection());

        $nth = $segments[0];

        $this->assertEquals('2018-02-01', $nth[0]->toDateString());
        $this->assertEquals('2018-02-28', $nth[1]->toDateString());

        $nth = $segments[1];

        $this->assertEquals('2018-03-01', $nth[0]->toDateString());
        $this->assertEquals('2018-03-31', $nth[1]->toDateString());

        $nth = $segments[2];

        $this->assertEquals('2018-04-01', $nth[0]->toDateString());
        $this->assertEquals('2018-04-30', $nth[1]->toDateString());


        // -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
        // -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

        $dateRange = new DateRange(
            TimeUtil::paramDateToCarbon('2018-01-01'),
            TimeUtil::paramDateToCarbon('2018-01-03')
        );

        $segments = collect($dateRange->segmentToDaysTupleCollection());

        $nth = $segments[0];

        $this->assertEquals('2018-01-01', $nth[0]->toDateString());
        $this->assertEquals('2018-01-01', $nth[1]->toDateString());

        $nth = $segments[1];

        $this->assertEquals('2018-01-02', $nth[0]->toDateString());
        $this->assertEquals('2018-01-02', $nth[1]->toDateString());

        $nth = $segments[2];

        $this->assertEquals('2018-01-03', $nth[0]->toDateString());
        $this->assertEquals('2018-01-03', $nth[1]->toDateString());

        // -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
        // -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

        $dateRange = new DateRange(
            TimeUtil::paramDateToCarbon('2018-01-01'),
            TimeUtil::paramDateToCarbon('2018-01-14')
        );

        $segments = collect($dateRange->segmentToWeeksTupleCollection());

        $nth = $segments[0];

        $this->assertEquals('2018-01-01', $nth[0]->toDateString());
        $this->assertEquals('2018-01-07', $nth[1]->toDateString());

        $nth = $segments[1];

        $this->assertEquals('2018-01-08', $nth[0]->toDateString());
        $this->assertEquals('2018-01-14', $nth[1]->toDateString());

        // -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
        // -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

        $dateRange = new DateRange(
            TimeUtil::paramDateToCarbon('2016-01-01'),
            TimeUtil::paramDateToCarbon('2018-01-01')
        );

        $segments = collect($dateRange->segmentToYearsTupleCollection());

        $nth = $segments[0];

        $this->assertEquals('2016-01-01', $nth[0]->toDateString());
        $this->assertEquals('2017-01-01', $nth[1]->toDateString());

        $nth = $segments[1];

        $this->assertEquals('2017-01-01', $nth[0]->toDateString());
        $this->assertEquals('2018-01-01', $nth[1]->toDateString());

//        foreach ($segments as $segment) {
//
//            echo "|-- {$segment[0]->toDateString()} --> {$segment[1]->toDateString()} \n";
//        }
//
//        dd("");
    }


    /**
     * @test
     */
    public function test_get_time_period_between()
    {

        // YEARS
        $dateRange = new DateRange(
            TimeUtil::now()->subYear(),
            TimeUtil::now()
        );

        $yearDates = $dateRange->getYearDatesBetween();

        $this->assertEquals(2, count($yearDates));

        $dateRange = new DateRange(
            TimeUtil::now()->subWeek(51),
            TimeUtil::now()
        );

        $yearDates = $dateRange->getYearDatesBetween();

        $this->assertEquals(1, count($yearDates));

        // MONTHS
        $dateRange = new DateRange(
            TimeUtil::now()->subMonth(5),
            TimeUtil::now()
        );

        $monthDates = $dateRange->getMonthDatesBetween();

        $this->assertEquals(6, count($monthDates));

        $dateRange = new DateRange(
            TimeUtil::now()->subWeek(3),
            TimeUtil::now()
        );

        $monthDates = $dateRange->getMonthDatesBetween();

        $this->assertEquals(1, count($monthDates));

        // WEEKS
        $dateRange = new DateRange(
            TimeUtil::now()->subWeeks(13),
            TimeUtil::now()
        );

        $weekDates = $dateRange->getWeekDatesBetween();

        $this->assertEquals(14, count($weekDates));

        $dateRange = new DateRange(
            TimeUtil::now()->subDay(6),
            TimeUtil::now()
        );

        $weekDates = $dateRange->getWeekDatesBetween();

        $this->assertEquals(1, count($weekDates));

        // DAYS

        $dateRange = new DateRange(
            TimeUtil::now()->subDay(45),
            TimeUtil::now()
        );

        $dates = $dateRange->getDatesBetween();

        $this->assertEquals(46, count($dates));

        $dateRange = new DateRange(
            TimeUtil::now()->subHour(6),
            TimeUtil::now()
        );

        $dates = $dateRange->getDatesBetween();

        $this->assertEquals(1, count($dates));
    }

    /**
     * @test
     */
    public function test_get_months_between_when_start_is_end_of_month()
    {
        $dateFrom = TimeUtil::paramDateToCarbon('2018-05-31');
        $dateTo = TimeUtil::paramDateToCarbon('2018-08-31');

        $dateRange = new DateRange($dateFrom, $dateTo);

        $dates = $dateRange->getMonthDatesBetween();

        $this->assertEquals(4, count($dates));
    }
}