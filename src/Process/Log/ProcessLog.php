<?php


namespace Logistio\Symmetry\Process\Log;


use Illuminate\Database\Eloquent\SoftDeletes;
use Logistio\Symmetry\Database\Models\BaseModel;
use Logistio\Symmetry\Process\State\ProcessState;

/**
 * Logistio\Symmetry\Process\Log\ProcessLog
 *
 * @property int $id
 * @property string|null $pubid
 * @property int $process_id
 * @property int $process_state_id
 * @property string $type
 * @property string|null $message
 * @property string|null $message_detail
 * @property string|null $payload
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Logistio\Symmetry\Process\Log\ProcessLog onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Log\ProcessLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Log\ProcessLog whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Log\ProcessLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Log\ProcessLog whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Log\ProcessLog whereMessageDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Log\ProcessLog wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Log\ProcessLog whereProcessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Log\ProcessLog whereProcessStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Log\ProcessLog wherePubid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Log\ProcessLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\Log\ProcessLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Logistio\Symmetry\Process\Log\ProcessLog withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Logistio\Symmetry\Process\Log\ProcessLog withoutTrashed()
 * @mixin \Eloquent
 */
class ProcessLog extends BaseModel
{
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */
    protected $table   = 'process_log';

    public $timestamps = true;

    public $pubIdColumn = true;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const TYPE_INFO = "INFO";
    const TYPE_WARNING = "WARNING";
    const TYPE_ERROR = "ERROR";

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function processState()
    {
        return $this->belongsTo(ProcessState::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Object Methods
    |--------------------------------------------------------------------------
    */

    /**
     * @param $payload
     */
    public function setPayload($payload)
    {
        $this->payload = json_encode($this->createPayloadArray($payload));
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

    /*
    |--------------------------------------------------------------------------
    | Repo Methods
    |--------------------------------------------------------------------------
    */

    /**
     * @param $processId
     * @param $processStateId
     * @param null $message
     * @param null $messageDetail
     * @param null $payload
     * @return ProcessLog
     */
    public static function logInfo($processId, $processStateId, $message = null, $messageDetail = null, $payload = null)
    {
        return static::log(static::TYPE_INFO, $processId, $processStateId, $message, $messageDetail, $payload);
    }

    /**
     * @param $processId
     * @param $processStateId
     * @param null $message
     * @param null $messageDetail
     * @param null $payload
     * @return ProcessLog
     */
    public static function logWarning($processId, $processStateId, $message = null, $messageDetail = null, $payload = null)
    {
        return static::log(static::TYPE_WARNING, $processId, $processStateId, $message, $messageDetail, $payload);
    }

    /**
     * @param $processId
     * @param $processStateId
     * @param null $message
     * @param null $messageDetail
     * @param null $payload
     * @return ProcessLog
     */
    public static function logError($processId, $processStateId, $message = null, $messageDetail = null, $payload = null)
    {
        return static::log(static::TYPE_ERROR, $processId, $processStateId, $message, $messageDetail, $payload);
    }

    /**
     * @param $type
     * @param $processId
     * @param $processStateId
     * @param null $message
     * @param null $messageDetail
     * @param null $payload
     * @return ProcessLog
     */
    public static function log($type, $processId, $processStateId, $message = null, $messageDetail = null, $payload = null)
    {
        $self = new self();

        $self->process_id = $processId;
        $self->process_state_id = $processStateId;
        $self->message = $message;
        $self->message_detail = $messageDetail;

        if (!is_null($payload)) {
            $self->payload = $self->setPayload($payload);
        }

        $self->type = $type;

        $self->save();

        return $self;
    }

}