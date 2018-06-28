<?php


namespace Logistio\Symmetry\Console\Commands\Database\PublicId;


use Logistio\Symmetry\Console\Commands\BaseArtisanCommand;
use Logistio\Symmetry\Database\Macro\PubId\TablePubIdColumnSetterManager;

class TablePubIdSetterCommand extends BaseArtisanCommand
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'symmetry:tables-pubid';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Sets the pubid column values for all tables specified in the config.';

    /**
     *
     */
    protected function executeHandle()
    {
       $tablesRequiringPubId = $this->getTablesRequiringPubId();

       $manager = new TablePubIdColumnSetterManager();

       $manager->setForTables($tablesRequiringPubId);
    }

    /**
     * @return array|mixed
     */
    private function getTablesRequiringPubId()
    {
       $config = config('symmetry.pubid_tables');

       if ($config) {
           return $config['tables'];
       }

       return [];
    }
}