<?php

namespace Logistio\Symmetry\Util\Time;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class DateRange implements Arrayable
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

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo
        ];
    }
}