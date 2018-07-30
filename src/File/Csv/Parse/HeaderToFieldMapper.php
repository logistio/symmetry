<?php

namespace Logistio\Symmetry\File\Csv\Parse;

/**
 * Class HeaderToFieldMapper
 * @package Logistio\Symmetry\File\Csv\Parse
 *
 * Maps an array of row values to their corresponding header position.
 */
class HeaderToFieldMapper
{
    /**
     * Specify whether or not to place `NULL` when a
     * corresponding field value is an empty string.
     *
     * @var bool
     */
    private $emptyStringToNull;

    /**
     * HeaderToFieldMapper constructor.
     * @param bool $emptyStringToNull
     */
    public function __construct($emptyStringToNull = false)
    {
        $this->emptyStringToNull = $emptyStringToNull;
    }

    /**
     * @param $fields
     * @param $headers
     * @param bool $emptyStringToNull
     * @return array
     */
    public static function mapFieldsToHeaders($fields, $headers, $emptyStringToNull)
    {
        $self = new self($emptyStringToNull);

        return $self->map($fields, $headers);
    }

    /**
     * @param $fields
     * @param $headers
     * @return array
     */
    public function map($fields, $headers)
    {
        $entries = [];

        foreach ($fields as $rowNum => $record) {
            $tmp = [];

            foreach ($headers as $idx => $field) {
                if (isset($record[$idx])) {

                    $value = $record[$idx];
                    if ($this->emptyStringToNull) {
                        $value = $this->setFieldToNullIfEmptyString($value);
                    }

                    $tmp[$field] = $value;
                } else {
                    $tmp[$field] = null;
                }
            }

            $entries[] = $tmp;
        }

        return $entries;
    }

    /**
     * Return null if the field is an empty string.
     *
     * @param $field
     * @return null
     */
    protected function setFieldToNullIfEmptyString($field)
    {
        if (!is_string($field)) {
            return $field;
        }

        if (strlen($field) == 0) {
            return null;
        }

        return $field;
    }
}