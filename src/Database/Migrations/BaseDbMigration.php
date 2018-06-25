<?php

namespace Logistio\Symmetry\Database\Migrations;

use Illuminate\Database\Migrations\Migration;

/**
 * BaseDbMigration
 * ----
 * Base Database Migration class.
 *
 */
abstract class BaseDbMigration extends Migration
{

    /**
     * Defines the Public Id (pubid) column.
     *
     * The value of this should always be the following:
     * 'pubid TEXT DEFAULT NULL'
     *
     * If the definition of the Public Id column changes in the future,
     * then create a new const to replace this, and keep v0 here.
     *
     * This will prevent migrations that have already been executed
     * from becoming un-reproducable.
     *
     * Version 0.
     * Effective Date: 2018-05-04.
     */
    protected const PUBLIC_ID_DEFINITION_V0 = 'pubid TEXT DEFAULT NULL';

    protected const CREATED_AT_DEFINITION_V0 = 'created_at TIMESTAMP NOT NULL';

    protected const UPDATED_AT_DEFINITION_V0 = 'updated_at TIMESTAMP DEFAULT NULL';

    protected const DELETED_AT_DEFINITION_V0 = 'deleted_at TIMESTAMP DEFAULT NULL';

    // ------------------------------------------------------------------------------

    protected $pubIdColumnDefinition;

    /**
     * BaseDbMigration constructor.
     */
    public function __construct()
    {
        $this->pubIdColumnDefinition = "{$this->getPubIdColumnName()} TEXT DEFAULT NULL";
    }

    public function getPubIdColumnName()
    {
        return \PublicId::getDatabaseColumn();
    }

    public function getPubIdDefinition_v0()
    {
        return self::PUBLIC_ID_DEFINITION_V0;
    }

    /**
     * Creates the UNIQUE contraint for the pubid column.
     *
     * @param $tableName
     * @return string
     *      The constraint definition, in the following format:
     *
     *      CREATE UNIQUE INDEX {$tableName}__pubid__unique ON {$tableName} (pubid)
     */
    public function getPubIdIndexDefinition_v0($tableName)
    {
        return "CREATE UNIQUE INDEX {$tableName}__pubid__unique ON {$tableName} (pubid)";
    }


    public function getCreatedUpdatedDeletedAt_v0()
    {
        return
            self::CREATED_AT_DEFINITION_V0 . ',
            ' . self::UPDATED_AT_DEFINITION_V0 . ',
            ' . self::DELETED_AT_DEFINITION_V0;
    }

}