<?php


namespace Logistio\Symmetry\Util\Type;


class PropertyTypeCaster
{
    const TYPE_FLOAT = 'FLOAT';
    const TYPE_INT = 'INT';

    /**
     * Type cast an array of objects.
     *
     * @param array $list
     * @param array $castConfig
     * @return array
     * @throws \Exception
     */
    public function typeCastObjectArray(array $list, array $castConfig = [])
    {
        foreach ($list as $index => $item) {
            foreach ($castConfig as $castItem) {

                $property = $castItem['property'];
                $type = $castItem['type'];

                if (!isset($item->{$property})) {
                    throw new \Exception("The property `{$property}` does not exist for item at index {$index}.");
                }

                $item->{$property} = $this->castValueForType($item->{$property}, $type);
            }
        }

        return $list;
    }

    /**
     * Type cast an array of arrays.
     * @param array $list
     * @param array $castConfig
     * @return array
     * @throws \Exception
     */
    public function typeCastAssociativeArray(array $list, array $castConfig = [])
    {
        foreach ($list as $index => &$item) {
            foreach ($castConfig as $castItem) {

                $property = $castItem['property'];
                $type = $castItem['type'];

                if (!isset($item[$property])) {
                    throw new \Exception("The property `{$property}` does not exist for item at index {$index}.");
                }

                $item[$property] = $this->castValueForType($item[$property], $type);
            }
        }

        return $list;
    }

    /**
     * @param $value
     * @param $type
     * @return float|int
     * @throws \Exception
     */
    public function castValueForType($value, $type)
    {
        if (is_null($value)) {
            return null;
        }

        switch ($type) {
            case static::TYPE_FLOAT: {
                return $this->castToFloat($value);
            }
            case static::TYPE_INT: {
                return $this->castToInt($value);
            }
            default: {
                throw new \Exception("Cast Type `{$type}` is not supported.");
            }
        }
    }

    /**
     * @param $value
     * @return float|null
     */
    public function castToFloat($value)
    {
        if (!is_numeric($value)) {
            return null;
        }

        return floatval($value);
    }

    /**
     * @param $value
     * @return int|null
     */
    public function castToInt($value)
    {
        if (!is_numeric($value)) {
            return null;
        }

        return intval($value);
    }

    /**
     * @return array
     */
    public function getAcceptedTypes()
    {
        return [
            static::TYPE_FLOAT,
            static::TYPE_INT
        ];
    }

    /**
     * @param $type
     * @return bool
     */
    public function isTypeAccepted($type)
    {
        return in_array($type, $this->getAcceptedTypes());
    }
}