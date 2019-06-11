<?php

namespace Logistio\Symmetry\Process\Payload;

use Illuminate\Contracts\Support\Arrayable;
use Logistio\Symmetry\Process\Process;

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
     * @var Process
     */
    protected $process;

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
     * @return Process
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @param Process $process
     */
    public function setProcess(Process $process)
    {
        $this->process = $process;
    }

    /**
     *
     */
    public function save()
    {
        if (!$this->process) {
            throw new \InvalidArgumentException("Process instance not set.");
        }

        $this->process->payload = json_encode($this->toArray());

        $this->process->save();
    }

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