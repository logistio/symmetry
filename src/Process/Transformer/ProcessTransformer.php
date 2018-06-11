<?php


namespace Logistio\Symmetry\Process\Transformer;


use Illuminate\Contracts\Support\Arrayable;
use League\Fractal\TransformerAbstract;
use Logistio\Symmetry\Database\Models\BaseModel;
use Logistio\Symmetry\Process\Process;

class ProcessTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'process_logs',
        'process_state'
    ];

    /**
     * @param Process $process
     * @return array
     */
    public function transform(Process $process)
    {
        $data = $process->toArray([
            'exclude_columns' => [[
                'key' => 'id'
            ], [
                'key' => 'process_state_id'
            ]],
            'transform_columns' => [[
                'key' => 'process_state_id',
                'output_key' => 'process_state_pubid',
                'callback' => BaseModel::makePubIdTransformationCallback()
            ]]
        ]);

        $payload = $process->getPayload();

        if ($payload) {
            if ($payload instanceof Arrayable) {
                $data['payload'] = $payload->toArray();
            }
            else {
                $data['payload'] = $payload;
            }
        }

        return $data;
    }

    /**
     * @param Process $process
     * @return \League\Fractal\Resource\Collection
     */
    public function includeProcessLogs(Process $process)
    {
        return $this->collection($process->processLogs, new ProcessLogTransformer());
    }

    /**
     * @param Process $process
     * @return \League\Fractal\Resource\Item
     */
    public function includeProcessState(Process $process)
    {
        return $this->item($process->processState, new ProcessStateTransformer());
    }
}