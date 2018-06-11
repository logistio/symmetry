<?php


namespace Logistio\Symmetry\Query\Macro\Cleaner;


use Logistio\Symmetry\Query\Macro\ColumnCode\ApiColumnCodeTag;

class QueryTokenCleaner
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
    public function clean($queryToken, $columnType)
    {
        $this->queryToken = $queryToken;
        $this->columnType = $columnType;

        if ($this->isNotSet()) {
            $this->queryToken = null;
        } else {
            $this->trim();

            $this->cleanForColumnTagType();
        }

        return $this->queryToken;
    }

    private function cleanForColumnTagType()
    {
        switch($this->columnType) {

            case ApiColumnCodeTag::TYPE_STRING: {
                $this->cleanForStringType();
                return;
            }
            case ApiColumnCodeTag::TYPE_NUMBER: {
                $this->cleanForNumberType();
                return;
            }
            case ApiColumnCodeTag::TYPE_DATETIME: {
                $this->cleanForDatetimeType();
                return;
            }
            default: {
                return;
            }

        }
    }

    private function cleanForStringType()
    {
        $this->lowerCase();
    }

    private function cleanForNumberType()
    {
        // Remove any non digit characters (except periods).
        $this->queryToken = preg_replace("/[^0-9.]/", "", $this->queryToken);

        // Make sure it is a number
        $this->queryToken = floatval($this->queryToken);
    }

    private function cleanForDatetimeType()
    {
        // Remove any non digit characters (except dashes and colons and spaces).
        $this->queryToken = preg_replace("/[^0-9-: ]/", "", $this->queryToken);
    }

    /**
     *
     */
    private function trim()
    {
        $this->queryToken = trim($this->queryToken);
    }

    private function lowerCase()
    {
        $this->queryToken = strtolower($this->queryToken);
    }

    private function isNotSet()
    {
        $tk = $this->queryToken;

        return (($tk == null) || ($tk == "") || (strlen($tk) == 0));
    }
}