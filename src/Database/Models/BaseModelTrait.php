<?php

namespace Logistio\Symmetry\Database\Models;

trait BaseModelTrait
{
    protected static function boot()
    {
        parent::boot();

        /**
         * Register a global created event hook.
         */
        static::created(function($model) {

            if ($model->hasPubIdColumn()) {
                $model->{\PublicId::getDatabaseColumn()} = \PublicId::encode($model->id);
                $model->save();
            }
        });
    }

    public function hasPubIdColumn()
    {
        return (true == $this->pubIdColumn);
    }

    /**
     * @return string
     */
    public function getPublicId()
    {
        if ($this->{\PublicId::getDatabaseColumn()}) {
            return $this->{\PublicId::getDatabaseColumn()};
        }

        return \PublicId::encode($this->id);
    }

    /**
     * Find an entity by its PublicId, or fail.
     *
     * This actually supports providing either a normal (DB primary key) id
     * or a PublicId, so there's no harm in replacing all calls to
     * 'findOrFail' with this method.
     *
     * @param string|int $publicId
     *      The PublicId or PrimaryKey of the entity to find.
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return static|self
     */
    public static function findOrFailByPubId($publicId)
    {
        $dbId = \PublicId::decodeSoft($publicId);
        return self::findOrFail($dbId);
    }

    /**
     * @param BaseModel $model
     * @param array $modelArray
     * @return array
     */
    private function attachMissingPubid(array $modelArray)
    {
        $pubid = array_get($modelArray, 'pubid', null);

        if (is_null($pubid)) {
            $modelArray['pubid'] = \PublicId::encode($this->id);
        }

        return $modelArray;
    }

    /**
     * @param null $options
     * @return array
     */
    public function toArray($options = null)
    {
        $array = parent::toArray();

        if ($this->pubIdColumn === true) {
            $array = $this->attachMissingPubid($array);
        }

        if (is_null($options)) {
            return $array;
        }

        $columnsTransform = array_get($options, 'columns_transform');

        if (is_array($columnsTransform)) {
            $array = $this->processTransformColumnsOption($array, $columnsTransform);
        }

        $excludeColumns = array_get($options, 'exclude_columns');

        if (is_array($excludeColumns)) {
            $array = $this->processExcludeColumnsOption($array, $excludeColumns);
        }

        $excludeId = array_get($options, 'exclude_id');

        if ($excludeId == true) {
            unset($array['id']);
        }

        return $array;
    }

    /**
     * @param array $modelArray
     * @param array $columnsToExclude
     * @return array
     */
    private function processExcludeColumnsOption(array $modelArray, array $columnsToExclude)
    {
        foreach ($columnsToExclude as $columnToExclude) {
            $columnKey = $columnToExclude['key'];

            $modelValue = array_get($modelArray, $columnKey);

            if (is_null($modelValue)) {
                continue;
            }

            unset($modelArray[$columnKey]);
        }

        return $modelArray;
    }

    private function processTransformColumnsOption(array $modelArray, array $transformColumnsOptions)
    {
        foreach ($transformColumnsOptions as $option) {
            $columnKey = $option['key'];
            $outputKey = $option['output_key'];

            /** @var \Closure $transformFunction */
            $transformFunction  = $option['callback'];

            $modelArray = call_user_func($transformFunction, $modelArray, $columnKey, $outputKey);
        }

        return $modelArray;
    }

    /**
     * Get the closure function to be used to transform
     * model ids to public ids.
     * @return \Closure
     */
    public static function makePubIdTransformationCallback()
    {
        return function(array $modelArray, $columnKey, $outputKey) {
            $modelValue = array_get($modelArray, $columnKey);

            if (is_null($modelValue)) {
                 return $modelArray;
            }

            $modelArray[$outputKey] = \PublicId::encode($modelValue);

            return $modelArray;
        };
    }
}