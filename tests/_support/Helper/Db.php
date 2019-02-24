<?php

namespace App\Tests\Helper;

use Codeception\Module\Db as CodeceptionDb;

/**
 * Class Db
 *
 * @package App\Tests\Helper
 */
class Db extends CodeceptionDb
{
    /**
     * @param array $settings
     *
     * @throws \Exception
     */
    public function _beforeSuite($settings = [])
    {
        parent::_beforeSuite($settings);

        if (isset($this->config['truncate_table'])) {
            $this->driver->getDbh()->exec('SET FOREIGN_KEY_CHECKS=0;');
            $this->driver->executeQuery("truncate table {$this->config['truncate_table']}", []);
            $this->driver->getDbh()->exec('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
