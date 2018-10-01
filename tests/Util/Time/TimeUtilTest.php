<?php

namespace Logistio\Symmetry\Test\Util\Time;

use Logistio\Symmetry\Test\TestCase;
use Logistio\Symmetry\Util\Time\DateRange;
use Logistio\Symmetry\Util\Time\TimeUtil;

class TimeUtilTest extends TestCase
{
    public function test()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function test_is_end_of_month()
    {
        $date = TimeUtil::paramDateToCarbon('2018-05-31');

        $this->assertTrue(TimeUtil::isEndOfMonth($date));

        $date = TimeUtil::paramDateToCarbon('2018-06-30');

        $this->assertTrue(TimeUtil::isEndOfMonth($date));

        $date = TimeUtil::paramDateToCarbon('2018-02-28');

        $this->assertTrue(TimeUtil::isEndOfMonth($date));

        $date = TimeUtil::paramDateToCarbon('2018-01-01');

        $this->assertFalse(TimeUtil::isEndOfMonth($date));
    }


    /**
     * @test
     */
    public function test_add_month()
    {
        $date = TimeUtil::paramDateToCarbon('2018-05-31');

        TimeUtil::addMonth($date);

        $this->assertEquals('2018-06-30', $date->toDateString());


        $date = TimeUtil::paramDateToCarbon('2018-02-28');

        TimeUtil::addMonth($date);

        $this->assertEquals('2018-03-31', $date->toDateString());


        $date = TimeUtil::paramDateToCarbon('2018-01-05');

        TimeUtil::addMonth($date);

        $this->assertEquals('2018-02-05', $date->toDateString());

        $date = TimeUtil::paramDateToCarbon('2018-08-01');

        TimeUtil::addMonth($date);

        $this->assertEquals('2018-08-31', $date->toDateString());

    }
}