<?php

namespace Logistio\Symmetry\File\Csv\Stream;

use Logistio\Symmetry\Exception\File\EmptyFileException;
use Logistio\Symmetry\File\Csv\Parse\CsvStringToArray;
use Logistio\Symmetry\File\Csv\Parse\HeaderToFieldMapper;

/**
 * Stream `mapped` rows from a CSV file resource.
 *
 * Class CsvFileBatchStreamer
 * @package Logistio\Symmetry\File\Csv\Stream
 */
class CsvFileBatchStreamer
{
    /**
     * @var int
     */
    protected $batchSize;

    /**
     * The file resource handle.
     *
     * @var resource
     */
    protected $resource;

    /**
     * @var array
     */
    protected $fileHeader;

    /**
     * @var int
     */
    protected $currentLine = 1;

    /**
     * @var CsvStringToArray
     */
    protected $csvStringParser;

    /**
     * CsvFileBatchStreamer constructor.
     * @param $resource
     * @param $delimiter
     * @param int $batchSize
     */
    public function __construct($resource, $delimiter, $batchSize = 500)
    {
        $this->resource = $resource;

        $this->csvStringParser = new CsvStringToArray($delimiter);

        $this->batchSize = $batchSize;
    }

    /**
     * Get the file header values.
     *
     * @return array
     * @throws EmptyFileException
     */
    public function getFileHeader()
    {
        if (is_null($this->fileHeader)) {
            $this->setFileHeader();
        }

        return $this->fileHeader;
    }

    /**
     * @return bool
     */
    public function isFileEmpty()
    {
        // Ensure the resource pointer is at the start:
        rewind($this->resource);

        $firstLine = fgets($this->resource);

        $this->setCursorToCurrentLine();

        return $firstLine === false;
    }

    /**
     * Get the next batch of lines from the CSV file.
     *
     * @return bool
     * @throws EmptyFileException
     */
    public function getNextBatch()
    {
        $batchString = $this->extractFileString();

        if (strlen($batchString) == 0) {
            return false;
        }

        $csvRows = $this->csvStringParser->parse($batchString);

        $this->currentLine += count($csvRows);

        return HeaderToFieldMapper::mapFieldsToHeaders(
            $csvRows, $this->getFileHeader(), true
        );
    }

    /**
     * Gets the column names of the file header and their associated row positions,
     * indexed by the column names.
     *
     * Example:
     *  [ 'address' => 2, 'shipment_id' => 0, 'tracking_number' => 1]
     */
    public function getIndexedFileHeader()
    {
        return array_flip($this->getFileHeader());
    }


    /**
     * @return string
     */
    protected function extractFileString()
    {
        $batchCounter = 0;

        $batchString = "";

        while ((!feof($this->resource)) && ($batchCounter < $this->batchSize)) {

            $batchString .= fgets($this->resource);

            $batchCounter++;
        }

        return $batchString;
    }

    /**
     * Set the file header as an array of
     * header values.
     */
    protected function setFileHeader()
    {
        // Ensure the resource pointer is at the start:
        rewind($this->resource);

        $line = fgets($this->resource);

        if ($line === false) {
            throw new EmptyFileException("The file is empty.");
        }

        $this->fileHeader = $this->csvStringParser->parseString($line)[0];

        $this->setCursorToCurrentLine();
    }

    /**
     * Set the file cursor to the current line.
     */
    protected function setCursorToCurrentLine()
    {
        $this->fastForward($this->currentLine);
    }

    /**
     * Fast forward the cursor to the given $lineNumber
     *
     * @param $lineNumber
     */
    protected function fastForward($lineNumber)
    {
        // Ensure the resource pointer is at the start:
        rewind($this->resource);

        for ($i = 0; $i < $lineNumber; $i++) {
            fgets($this->resource);
        }
    }
}