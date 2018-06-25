<?php


namespace Logistio\Symmetry\Util\File\Csv;


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

    /**
     * @param $fileString
     * @param $delimiter
     * @param null $enclosure
     * @param null $escape
     * @return array
     */
    public static function parse($fileString, $delimiter, $enclosure = null, $escape = null)
    {
        $self = new self($delimiter, $enclosure, $escape);

        return $self->parseString($fileString);
    }

    /**
     * @param $fileString
     * @return array
     */
    public function parseString($fileString)
    {
        $rows = $this->getRows($fileString);

        $csvRows = [];

        foreach ($rows as $row) {

            $rowCells = str_getcsv($row, $this->delimiter, $this->enclosure, $this->escape);

            $rowCells = $this->cleanRowCells($rowCells);


            $csvRows[] = $rowCells;

        }//End of $row

        /*
         * Remove empty lines at the end
         */
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