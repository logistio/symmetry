<?php


namespace Logistio\Symmetry\Process;

use Logistio\Symmetry\Database\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Logistio\Symmetry\Process\Log\ProcessLog;
use Logistio\Symmetry\Process\State\ProcessState;

/**
 * Logistio\Symmetry\Process\Process
 *
 * @property int $id
 * @property string|null $pubid
 * @property int|null $external_id
 * @property int $process_state_id
 * @property string|null $process_state_detail
 * @property string $process_state_at
 * @property string $type
 * @property string|null $payload
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Logistio\Symmetry\Process\Log\ProcessLog[] $processLogs
 * @property-read \Logistio\Symmetry\Process\State\ProcessState $processState
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Logistio\Symmetry\Process\Process onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Process whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Process whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Process whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Process whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Process wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Process whereProcessStateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Process whereProcessStateDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Process whereProcessStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Process wherePubid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Process whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Process whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Logistio\Symmetry\Process\Process withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Logistio\Symmetry\Process\Process withoutTrashed()
 * @mixin \Eloquent
 */
class Process extends BaseModel
{
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */
    protected $table   = 'process';

    public $timestamps = true;

    public $pubIdColumn = true;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function processState()
    {
        return $this->hasOne(ProcessState::class, 'id', 'process_state_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function processLogs()
    {
        return $this->hasMany(ProcessLog::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Object Methods
    |--------------------------------------------------------------------------
    */

    /**
     * @param ProcessState $state
     */
    public function setState(ProcessState $state)
    {
        $this->process_state_id = $state->id;
    }

    public function getPayload()
    {
        if ($this->payload) {
            $data = json_decode($this->payload, 'true')['data'];

            return unserialize($data);
        }

        return null;
    }

    /**
     * @param $payload
     * @return $this
     */
    public function setPayload($payload)
    {
        $this->payload = json_encode($this->createPayloadArray($payload));
        return $this;
    }

    /**
     * @param $payload
     * @return array
     */
    protected function createPayloadArray($payload)
    {
        return [
            'data' => serialize($payload)
        ];
    }

    /**
     * @param $payload
     * @return string
     */
    protected function serializePayloadObject($payload)
    {
        return serialize($payload);
    }

    /**
     * Determine if the process' execution is complete.
     * This method does not determine the success or
     * failure of the process.
     *
     * @return bool
     */
    public function isProcessExecutionComplete()
    {
        $states = ProcessState::getInProgressAndPendingStates()->pluck('id')
            ->toArray();

        $processStateId = $this->process_state_id;

        return !in_array($processStateId, $states);
    }

    /*
    |--------------------------------------------------------------------------
    | Repo Methods
    |--------------------------------------------------------------------------
    */

    /**
     * @param $externalId
     * @param int $countQuery
     * @param null $type
     * @return bool
     */
    public static function isProcessRunningForExternalId($externalId, $countQuery = 0, $type = null)
    {
        $states = ProcessState::getInProgressAndPendingStates();

        $query = static::where('external_id', $externalId)
            ->whereIn('process_state_id', $states->pluck('id')->toArray());

        if ($type) {
            $query->whereType($type);
        }

        $processes = $query->get();

        return $processes->count() > $countQuery;
    }

    /**
     * @param Process $process
     */
    public static function forceDeleteProcess(Process $process)
    {
        foreach ($process->processLogs as $processLog) {
            $processLog->forceDelete();
        }

        $process->forceDelete();
    }
}