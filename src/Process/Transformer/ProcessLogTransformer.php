<?php


namespace Logistio\Symmetry\Process\Transformer;


use League\Fractal\TransformerAbstract;
use Logistio\Symmetry\Database\Models\BaseModel;
use Logistio\Symmetry\Process\Log\ProcessLog;

class ProcessLogTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'process_state'
    ];

    /**
     * @param ProcessLog $processLog
     * @return array
     */
    public function transform(ProcessLog $processLog)
    {
        return $processLog->toArray([
            'exclude_columns' => [[
                'key' => 'id'
            ], [
                'key' => 'process_id'
            ], [
                'key' => 'process_state_id'
            ]],
            'columns_transform' => [[
                'key' => 'process_id',
                'output_key' => 'process_pubid',
                'callback' => BaseModel::makePubIdTransformationCallback()
            ], [
                'key' => 'process_state_id',
                'output_key' => 'process_state_pubid',
                'callback' => BaseModel::makePubIdTransformationCallback()
            ]]
        ]);
    }

    /**
     * @param ProcessLog $processLog
     * @return \League\Fractal\Resource\Item
     */
    public function includeProcessState(ProcessLog $processLog)
    {
        return $this->item($processLog->processState, new ProcessStateTransformer());
    }
}