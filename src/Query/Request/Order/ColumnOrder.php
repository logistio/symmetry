<?php


namespace Logistio\Symmetry\Query\Request\Order;

/**
 * Class ColumnOrder
 * @package Logistio\Symmetry\Query\Request\Order
 */
class ColumnOrder
{
    const ASCENDING_ORDER = 'ASC';
    const DESCENDING_ORDER = 'DESC';

    /**
     * The name of the column of the data source
     * for which to apply the ordering.
     * The query processor will determine the mapping between
     * the column names in the request and column names
     * in the source data repository.
     *
     * @var string
     */
    private $columnName;

    /**
     * @var
     */
    private $apiColumnCode;

    /**
     * The order direction.
     *
     * @var string
     */
    private $direction;

    /**
     * ColumnOrder constructor.
     * @param string $apiColumnCode
     * @param string $direction
     */
    public function __construct($apiColumnCode, $direction)
    {
        $this->apiColumnCode= $apiColumnCode;
        $this->direction = strtoupper($direction);
    }



    /**
     * @param $direction
     * @return bool
     */
    public static function isDirectionValid($direction)
    {
        return in_array(strtoupper($direction), [
            static::ASCENDING_ORDER,
            static::DESCENDING_ORDER
        ]);
    }

    /**
     * @return string
     */
    public function getColumnName(): string
    {
        return $this->columnName;
    }

    /**
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * @return mixed
     */
    public function getApiColumnCode()
    {
        return $this->apiColumnCode;
    }

    /**
     * @param $columnName
     */
    public function setColumnName($columnName)
    {
        $this->columnName = $columnName;
    }
}