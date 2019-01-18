<?php

namespace Logistio\Symmetry\Process\Concerns;

use Logistio\Symmetry\Exception\System\ExecutionInterruptedException;
use Logistio\Symmetry\Process\Log\ProcessLog;
use Logistio\Symmetry\Process\Payload\ProcessPayload;
use Logistio\Symmetry\Process\State\ProcessState;
use Logistio\Symmetry\Util\Time\TimeUtil;

/**
 * Trait ManagesProcess
 * @package Logistio\Symmetry\Process\Concerns
 */
trait ManagesProcess
{
    /**
     * @param $logMessage
     */
    protected function setInProgressState($logMessage)
    {
        $inProgressState = ProcessState::findByCode(ProcessState::CODE_IN_PROGRESS);

        $this->process->process_state_id = $inProgressState->id;
        $this->process->process_state_at = TimeUtil::now()->toDateTimeString();
        $this->process->save();

        ProcessLog::logInfo(
            $this->process->id,
            $this->process->process_state_id,
            $logMessage
        );
    }

    /**
     * @param $logMessage
     */
    protected function setProcessAsCompleted($logMessage)
    {
        $completedState = ProcessState::findByCode(ProcessState::CODE_COMPLETED);

        $this->process->process_state_id = $completedState->id;
        $this->process->process_state_at = TimeUtil::now()->toDateTimeString();
        $this->process->save();

        ProcessLog::logInfo(
            $this->process->id,
            $this->process->process_state_id,
            $logMessage
        );
    }

    /**
     * @param ExecutionInterruptedException $e
     * @param string $logMessage
     */
    protected function setAsPausedStateFromExecutionInterruption(ExecutionInterruptedException $e, $logMessage = "The process has been paused by an external component.")
    {
        $interruptedState = ProcessState::findByCode(ProcessState::CODE_PAUSED);

        $this->process->process_state_id = $interruptedState->id;
        $this->process->process_state_at = TimeUtil::now()->toDateTimeString();
        $this->process->save();

        ProcessLog::logWarning(
            $this->process->id,
            $this->process->process_state_id,
            $logMessage
        );
    }

    /**
     * @param \Exception $e
     */
    protected function setAsFailedFromGeneralException(\Exception $e)
    {
        $failedState = ProcessState::findByCode(ProcessState::CODE_FAILED);

        $this->process->process_state_id = $failedState->id;
        $this->process->process_state_at = TimeUtil::now()->toDateTimeString();
        $this->process->save();

        $logMessage = "An unhandled error has occurred.";

        $exceptionLogicalName = get_class($e);
        $exceptionMessage = $e->getMessage();
        $exceptionCode = $e->getCode();

        $trace = $e->getTraceAsString();

        $logMessageDetail = "[Exception Name: {$exceptionLogicalName}] [Message: {$exceptionMessage}] [Code: {$exceptionCode}] \n Trace: \n " . $trace;

        ProcessLog::logError(
            $this->process->id,
            $this->process->process_state_id,
            $logMessage,
            $logMessageDetail
        );
    }

    /**
     * @return bool
     */
    protected function isRunningInIncubatorMode()
    {
        $processPayload = $this->process->getPayload();

        if ($processPayload && $processPayload instanceof ProcessPayload) {
            return $processPayload->isRunningInIncubatorMode();
        }

        return false;
    }

}