<?php

namespace Logistio\Symmetry\Process\Payload;

use Illuminate\Contracts\Support\Arrayable;

abstract class ProcessPayload implements Arrayable
{
    public $incubatorMode = false;

    /**
     * The client state is a model which may be set
     * by the process 'client', i.e. the task
     * or activity that the process is wrapping.
     *
     * For example if the process wraps an XA calculation,
     * the clientState may have properties such as how
     * many dates are there to process, how many dates
     * have been processed etc.
     *
     * @var ProcessClientState
     */
    public $clientState = null;

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

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'client_state' => $this->clientState ? $this->clientState->toArray() : null,
            'incubator_mode' => $this->isRunningInIncubatorMode(),
        ];
    }
}