<?php


namespace Logistio\Symmetry\Query\Macro\Validator;


use Logistio\Symmetry\Exception\ValidationException;
use Logistio\Symmetry\Query\Macro\ColumnCode\ApiColumnCodeTag;
use Logistio\Symmetry\Util\Time\TimeUtil;

class QueryTokenValidator
{
    /**
     * @var string|int|float
     */
    private $queryToken;

    /**
     * @var string
     */
    private $columnType;

    /**
     * @param $queryToken
     * @param $columnType
     * @return float|int|string
     */
    public function validate($queryToken, $columnType)
    {
        $this->queryToken = $queryToken;
        $this->columnType = $columnType;

        $this->checkIfNull();

        $this->validateForColumnTagType();
    }

    /**
     * @throws ValidationException
     */
    private function checkIfNull()
    {
        if (is_null($this->queryToken)) {
            throw new ValidationException("A query token cannot be null.");
        }
    }

    private function validateForColumnTagType()
    {
        switch($this->columnType) {

            case ApiColumnCodeTag::TYPE_STRING: {
                $this->validateForStringType();
                return;
            }
            case ApiColumnCodeTag::TYPE_NUMBER: {
                $this->validateForNumberType();
                return;
            }
            case ApiColumnCodeTag::TYPE_DATETIME: {
                $this->validateForDatetimeType();
                return;
            }
            default: {
                return;
            }

        }
    }

    /**
     *
     */
    private function validateForStringType()
    {
        //
    }

    /**
     * @throws ValidationException
     */
    private function validateForNumberType()
    {
        $tk = $this->queryToken;

        if (!is_string($this->queryToken)) {
            $tk = strval($this->queryToken);
        }

        if (!is_numeric($tk)) {
            throw new ValidationException("Numeric query tokens must be valid real numbers. Received `{$this->queryToken}`.");
        }
    }

    /**
     *
     */
    private function validateForDatetimeType()
    {

    }

}