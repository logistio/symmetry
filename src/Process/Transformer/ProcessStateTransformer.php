<?php


namespace Logistio\Symmetry\Process\Transformer;


use League\Fractal\TransformerAbstract;
use Logistio\Symmetry\Process\State\ProcessState;

class ProcessStateTransformer extends TransformerAbstract
{
    /**
     * @param ProcessState $processState
     * @return array
     */
    public function transform(ProcessState $processState)
    {
        return $processState->toArray([
            'exclude_id' => true
        ]);
    }
}