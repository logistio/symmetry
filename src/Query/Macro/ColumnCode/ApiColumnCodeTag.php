<?php


namespace Logistio\Symmetry\Query\Macro\ColumnCode;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Class ApiColumnCodeTag
 *
 * Describes the mapping of an api_column_code property
 * to the column names of the data source for
 * ordering, filtering, etc.
 */
class ApiColumnCodeTag
{
    const TYPE_STRING = 'STRING';
    const TYPE_NUMBER = 'NUMBER';
    const TYPE_DATETIME = 'DATETIME';
    const TYPE_DATE = 'DATE';
    const TYPE_TIME = 'TIME';

    private $type;

    private $code;

    private $databaseColumn;

    private $timeAggregateScope;

    /**
     * @var
     */
    private $selectAlias;

    /**
     * @param $config
     * @return Collection
     */
    public static function makeFromArray($config)
    {
        $collection = new Collection();

        foreach ($config as $apiColumnCodeConfig) {
            $tag = new self();

            $tag->setCode($apiColumnCodeConfig['code']);
            $tag->setDatabaseColumn($apiColumnCodeConfig['database_column']);

            $type = Arr::get($apiColumnCodeConfig, 'type', static::TYPE_STRING);

            $tag->setType($type);

            $timeAggregateScope = Arr::get($apiColumnCodeConfig, 'time_aggregate_scope', null);

            $tag->setTimeAggregateScope($timeAggregateScope);

            $alias = Arr::get($apiColumnCodeConfig, 'select_alias', null);

            if ($alias) {
                $tag->setSelectAlias($alias);
            }

            $collection->push($tag);
        }

        return $collection;
    }

    /**
     * @return mixed
     */
    public function getTimeAggregateScope()
    {
        return $this->timeAggregateScope;
    }

    /**
     * @param mixed $timeAggregateScope
     */
    public function setTimeAggregateScope($timeAggregateScope): void
    {
        $this->timeAggregateScope = $timeAggregateScope;
    }

    /**
     * @return mixed
     */
    public function getDatabaseColumn()
    {
        return $this->databaseColumn;
    }

    /**
     * @param mixed $databaseColumn
     */
    public function setDatabaseColumn($databaseColumn): void
    {
        $this->databaseColumn = $databaseColumn;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getSelectAlias()
    {
        return $this->selectAlias;
    }

    /**
     * @param mixed $selectAlias
     */
    public function setSelectAlias($selectAlias): void
    {
        $this->selectAlias = $selectAlias;
    }

    /**
     * @return bool
     */
    public function isTypeString()
    {
        return static::TYPE_STRING == $this->getType();
    }

    /**
     * @return bool
     */
    public function isTypeNumber()
    {
        return static::TYPE_NUMBER == $this->getType();
    }

    /**
     * @return bool
     */
    public function isTypeDatetime()
    {
        return static::TYPE_DATETIME == $this->getType();
    }

    /**
     * @return bool
     */
    public function isTypeDate()
    {
        return static::TYPE_DATE == $this->getType();
    }

    /**
     * @return bool
     */
    public function isTypeTime()
    {
        return static::TYPE_TIME == $this->getType();
    }
}