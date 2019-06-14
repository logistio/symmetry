<?php

namespace Logistio\Symmetry\Test\Util\Time;

use Carbon\Carbon;
use DateTime;
use Logistio\Symmetry\Test\TestCase;
use Logistio\Symmetry\Util\Time\DateRange;
use Logistio\Symmetry\Util\Time\TimeUtil;

class TimeUtilTest extends TestCase
{
    public function test()
    {
        self::assertTrue(true);
    }

    /**
     * @test
     */
    public function test_is_end_of_month()
    {
        $date = TimeUtil::paramDateToCarbon('2018-05-31');

        self::assertTrue(TimeUtil::isEndOfMonth($date));

        $date = TimeUtil::paramDateToCarbon('2018-06-30');

        self::assertTrue(TimeUtil::isEndOfMonth($date));

        $date = TimeUtil::paramDateToCarbon('2018-02-28');

        self::assertTrue(TimeUtil::isEndOfMonth($date));

        $date = TimeUtil::paramDateToCarbon('2018-01-01');

        self::assertFalse(TimeUtil::isEndOfMonth($date));
    }


    /**
     * @test
     */
    public function test_add_month()
    {
        $date = TimeUtil::paramDateToCarbon('2018-05-31');

        TimeUtil::addMonth($date);

        self::assertEquals('2018-06-30', $date->toDateString());


        $date = TimeUtil::paramDateToCarbon('2018-02-28');

        TimeUtil::addMonth($date);

        self::assertEquals('2018-03-31', $date->toDateString());


        $date = TimeUtil::paramDateToCarbon('2018-01-05');

        TimeUtil::addMonth($date);

        self::assertEquals('2018-02-05', $date->toDateString());

        $date = TimeUtil::paramDateToCarbon('2018-08-01');

        TimeUtil::addMonth($date);

        self::assertEquals('2018-08-31', $date->toDateString());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function test_sub_month()
    {
        $date = TimeUtil::paramDateToCarbon('2018-08-31');

        TimeUtil::subMonth($date);

        self::assertEquals('2018-08-01', $date->toDateString());

        $date = TimeUtil::paramDateToCarbon('2018-08-01');

        TimeUtil::subMonth($date);

        self::assertEquals('2018-07-01', $date->toDateString());

        $date = TimeUtil::paramDateToCarbon('2018-08-05');

        TimeUtil::subMonth($date);

        self::assertEquals('2018-07-05', $date->toDateString());
    }

    /**
     * @test
     */
    public function testFromCarbonToCalendarDate()
    {
        $expectedDate = '2006-09-22';
        $carbonDate = TimeUtil::apiDateToCarbon('2006-09-22');

        $date = TimeUtil::fromCarbonToDate($carbonDate);

        self::assertEquals($expectedDate, $date);
    }

    /**
     * Asserts that we can pass a DateTime and a Carbon to "TimeUtil::dateTimeToApiTimestamp".
     * @test
     */
    public function testDateTimeToApiTimestamp()
    {
        $expectedTimestamp = '2019-03-01 10:08:19';
        $instant = DateTime::createFromFormat('Y-m-d H:i:s', $expectedTimestamp);
        $convertedTimestamp = TimeUtil::dateTimeToApiTimestamp($instant);
        self::assertEquals($expectedTimestamp, $convertedTimestamp);

        $expectedTimestamp = '2019-06-06 13:12:11';
        $instant = Carbon::createFromFormat('Y-m-d H:i:s', $expectedTimestamp);
        $convertedTimestamp = TimeUtil::dateTimeToApiTimestamp($instant);
        self::assertEquals($expectedTimestamp, $convertedTimestamp);
    }


}