<?php

use Illuminate\Database\Eloquent\SoftDeletes;

class _TemplateModel extends BaseModel
{
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */
    protected $table   = 'template';

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
}