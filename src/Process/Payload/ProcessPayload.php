<?php


namespace Logistio\Symmetry\Process\Payload;


use Illuminate\Contracts\Support\Arrayable;

abstract class ProcessPayload implements Arrayable
{
    public $incubatorMode = false;

    /**
     * @return bool
     */
    public function isRunningInIncubatorMode()
    {
        return true == $this->incubatorMode;
    }

    /**
     * @return string
     */
    public abstract function getType();
}