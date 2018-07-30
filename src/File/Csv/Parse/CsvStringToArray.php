<?php

namespace Logistio\Symmetry\File\Csv\Parse;

/**
 * Class CsvStringToArray
 * @package Logistio\Symmetry\File\Csv\Parse
 *
 * Convert a CSV formatted string to an associative array of rows.
 */
class CsvStringToArray
{
    /**
     * @var string
     */
    private $delimiter;

    /**
     * @var string
     */
    private $enclosure;

    /**
     * @var string
     */
    private $escape;

    /**
     * CsvStringToArray constructor.
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     */
    public function __construct($delimiter, $enclosure = null, $escape = null)
    {
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
    }

    public function parse($fileString)
    {
        $rows = $this->getRows($fileString);

        $csvRows = [];

        foreach ($rows as $row) {

            $rowCells = str_getcsv($row, $this->delimiter, $this->enclosure, $this->escape);

            $rowCells = $this->cleanRowCells($rowCells);


            $csvRows[] = $rowCells;

        }

        // Remove empty lines that may be incorrectly parsed
        while (count($csvRows) && (!$last = trim(implode('', $csvRows[count($csvRows) - 1])))) {
            array_pop($csvRows);
        }

        return $csvRows;
    }

    /**
     * Transform each new line as an array element.
     *
     * @param $fileString
     * @return array
     */
    public function getRows($fileString)
    {
        $rows = preg_split("/\r\n|\n|\r/", $fileString);
        return $rows;
    }

    /**
     * @param array $rowCells
     * @return array
     */
    protected function cleanRowCells(array $rowCells)
    {
        return array_map(function($cell) {

            /*
             * Trim the cell value
             */
            return trim($cell);

        }, $rowCells);
    }
}