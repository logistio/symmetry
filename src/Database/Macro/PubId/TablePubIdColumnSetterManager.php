<?php


namespace Logistio\Symmetry\Database\Macro\PubId;


use Logistio\Symmetry\Database\Util\Table\DbTableUtil;

class TablePubIdColumnSetterManager
{
    /**
     * @param array $tables
     */
    public function setForTables(array $tables)
    {
        foreach ($tables as $table) {

            $this->validate($table);

            $columnSetter = new TablePubIdColumnSetter($table);
            $columnSetter->setMissingEntries();

        }
    }

    /**
     * @param $table
     */
    private function validate($table)
    {
        $this->validateTableExists($table);
    }

    /**
     * @param $table
     */
    private function validateTableExists($table)
    {
        if (!DbTableUtil::doesTableExist($table)) {

            throw new \InvalidArgumentException("Table `{$table}` does not exist.");
        }
    }
}