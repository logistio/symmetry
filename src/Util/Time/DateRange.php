<?php

namespace Logistio\Symmetry\Util\Time;

use Carbon\Carbon;

class DateRange
{
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
     */
    public function __construct(Carbon $dateFrom, Carbon $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
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
}