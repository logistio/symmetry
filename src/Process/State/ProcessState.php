<?php


namespace Logistio\Symmetry\Process\State;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Logistio\Symmetry\Database\Models\BaseModel;

/**
 * Logistio\Symmetry\Process\State\ProcessState
 *
 * @property int $id
 * @property string|null $pubid
 * @property string $code
 * @property string|null $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Logistio\Symmetry\Process\State\ProcessState onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\State\ProcessState whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\State\ProcessState whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\State\ProcessState whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\State\ProcessState whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\State\ProcessState whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\State\ProcessState wherePubid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Logistio\Symmetry\Process\State\ProcessState whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Logistio\Symmetry\Process\State\ProcessState withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Logistio\Symmetry\Process\State\ProcessState withoutTrashed()
 * @mixin \Eloquent
 */
class ProcessState extends BaseModel
{
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */
    protected $table   = 'process_state';

    public $timestamps = true;

    public $pubIdColumn = true;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const CODE_PENDING = 'PENDING';
    const CODE_IN_PROGRESS = 'IN_PROGRESS';
    const CODE_PAUSED = 'PAUSED';
    const CODE_CANCELLED = 'CANCELLED';
    const CODE_COMPLETED = 'COMPLETED';
    const CODE_FAILED = 'FAILED';

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

    /*
    |--------------------------------------------------------------------------
    | Object Methods
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Repo Methods
    |--------------------------------------------------------------------------
    */

    /**
     * @param $code
     * @return ProcessState|null
     */
    public static function findByCode($code)
    {
        return static::where('code', $code)->first();
    }

    /**
     * @return ProcessState|null
     */
    public static function getInProgressState()
    {
        return static::findByCode(static::CODE_IN_PROGRESS);
    }

    /**
     * @return ProcessState|null
     */
    public static function getPendingState()
    {
        return static::findByCode(static::CODE_PENDING);
    }

    /**
     * @return ProcessState|null
     */
    public static function getCompletedState()
    {
        return static::findByCode(static::CODE_COMPLETED);
    }

    /**
     * @return ProcessState|null
     */
    public static function getFailedState()
    {
        return static::findByCode(static::CODE_FAILED);
    }

    /**
     * @return ProcessState|null
     */
    public static function getPausedState()
    {
        return static::findByCode(static::CODE_PAUSED);
    }

    /**
     * Get the states that signify that the process
     * is being executed or pending execution.
     *
     * @return Collection|PassengerState[]
     */
    public static function getInProgressAndPendingStates()
    {
        return static::whereIn('code', [
            static::CODE_PENDING,
            static::CODE_IN_PROGRESS
        ])
            ->get();
    }
}