<?php


namespace Logistio\Symmetry\Process\Query;

use Illuminate\Support\Collection;
use Logistio\Symmetry\Query\Macro\ColumnCode\ApiColumnCodeTag;

class ProcessApiColumnCodeConfig
{
    public static $config = [[
        'code' => 'id',
        'database_column' => 'process.id',
        'type' => ApiColumnCodeTag::TYPE_NUMBER,
    ], [
        'code' => 'pubid',
        'database_column' => 'process.pubid',
        'type' => ApiColumnCodeTag::TYPE_STRING,
    ], [
        'code' => 'external_id',
        'database_column' => 'process.external_id',
        'type' => ApiColumnCodeTag::TYPE_STRING,
    ], [
        'code' => 'process_state',
        'database_column' => 'process_state.code',
        'type' => ApiColumnCodeTag::TYPE_STRING,
    ], [
        'code' => 'type',
        'database_column' => 'process.type',
        'type' => ApiColumnCodeTag::TYPE_STRING,
    ], [
        'code' => 'created_at',
        'database_column' => 'process.created_at',
        'type' => ApiColumnCodeTag::TYPE_DATETIME,
    ], [
        'code' => 'process_state_at',
        'database_column' => 'process.process_state_at',
        'type' => ApiColumnCodeTag::TYPE_DATETIME,
    ]];

    /**
     * @return Collection
     */
    public static function makeCollection()
    {
        $collection = new Collection();

        foreach (static::$config as $apiColumnCodeConfig) {
            $tag = new ApiColumnCodeTag();

            $tag->setCode($apiColumnCodeConfig['code']);
            $tag->setDatabaseColumn($apiColumnCodeConfig['database_column']);
            $tag->setType($apiColumnCodeConfig['type']);

            $collection->push($tag);
        }

        return $collection;
    }
}