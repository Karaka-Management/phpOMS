<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\Schema;

use phpOMS\DataStorage\Database\Connection\MysqlConnection;
use phpOMS\DataStorage\Database\Schema\Builder;

/**
 * @internal
 */
class BuilderTest extends \PHPUnit\Framework\TestCase
{
    protected $con = null;

    protected function setUp() : void
    {
        $this->con = new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin']);
    }

    public function testMysqlDrop() : void
    {
        $query = new Builder($this->con);
        $sql   = 'DROP DATABASE `test`;';
        self::assertEquals($sql, $query->dropDatabase('test')->toSql());
    }

    public function testMysqlShowTables() : void
    {
        $query = new Builder($this->con);
        $sql   = 'SELECT `table_name` FROM `information_schema`.`tables` WHERE `table_schema` = \'' . $GLOBALS['CONFIG']['db']['core']['masters']['admin']['database']. '\';';
        self::assertEquals($sql, $query->selectTables()->toSql());
    }

    public function testMysqlShowFields() : void
    {
        $query = new Builder($this->con);
        $sql   = 'SELECT * FROM `information_schema`.`columns` WHERE `table_schema` = \'' . $GLOBALS['CONFIG']['db']['core']['masters']['admin']['database']. '\' AND `table_name` = \'test\';';
        self::assertEquals($sql, $query->selectFields('test')->toSql());
    }

    public function testMysqlCreateTable() : void
    {
        $query = new Builder($this->con);
        $sql   = 'CREATE TABLE `user_roles` (`user_id` INT NOT NULL AUTO_INCREMENT, `role_id` VARCHAR(10) DEFAULT \'1\' NULL, PRIMARY KEY (`user_id`), FOREIGN KEY (`user_id`) REFERENCES `users` (`ext1_id`), FOREIGN KEY (`role_id`) REFERENCES `roles` (`ext2_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;';
        self::assertEquals(
            $sql,
            $query->createTable('user_roles')
                ->field('user_id', 'INT', null, false, true, true, 'users', 'ext1_id')
                ->field('role_id', 'VARCHAR(10)', '1', true, false, false, 'roles', 'ext2_id')
            ->toSql()
        );
    }
}
