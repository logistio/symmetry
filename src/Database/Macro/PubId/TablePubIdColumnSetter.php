<?php

namespace Logistio\Symmetry\Database\Macro\PubId;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Logistio\Symmetry\PublicId\PublicIdConverter;

class TablePubIdColumnSetter
{
    private $tableName;

    /**
     * TablePubIdColumnSetter constructor.
     * @param $tableName
     */
    public function __construct($tableName)
    {
        $this->tableName = $tableName;
    }

    public function setMissingEntries()
    {
        $this->validateThatPubIdColumnExists();

        foreach ($this->getNullEntriesQuery()->cursor() as $entry) {

            $id = $entry->id;

            $pubId = \PublicId::encode($id);

            $entry->pubid = $pubId;

            $this->updateEntry($id, $pubId);
        }
    }

    private function updateEntry($entryId, $pubId)
    {
        \DB::table($this->tableName)
            ->where('id', $entryId)
            ->update([
                'pubid' => $pubId
            ]);
    }


    /**
     *
     */
    private function getNullEntriesQuery()
    {
        return \DB::table($this->tableName)
            ->whereNull(PublicIdConverter::DATABASE_COLUMN);
    }

    /**
     *
     */
    private function validateThatPubIdColumnExists()
    {
        $column = PublicIdConverter::DATABASE_COLUMN;

        if (!Schema::hasColumn($this->tableName, $column)) {
            throw new \InvalidArgumentException("Column or field `{$column}` does not exist in table `{$this->tableName}`");
        }
    }
}