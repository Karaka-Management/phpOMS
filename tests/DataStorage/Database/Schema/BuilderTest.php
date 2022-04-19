<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\Schema;

use phpOMS\DataStorage\Database\Connection\MysqlConnection;
use phpOMS\DataStorage\Database\Schema\Builder;

/**
 * @testdox phpOMS\tests\DataStorage\Database\Schema\BuilderTest: Query builder for sql schemas
 *
 * @internal
 */
final class BuilderTest extends \PHPUnit\Framework\TestCase
{
    protected MysqlConnection $con;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->con = new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin']);
    }

    /**
     * @testdox Mysql database drop forms a valid query
     * @group framework
     */
    public function testMysqlDrop() : void
    {
        $query = new Builder($this->con);
        $sql   = 'DROP DATABASE `test`;';
        self::assertEquals($sql, $query->dropDatabase('test')->toSql());
    }

    /**
     * @testdox Mysql table drop forms a valid query
     * @group framework
     */
    public function testMysqlDropTable() : void
    {
        $query = new Builder($this->con);
        $sql   = 'DROP TABLE `test`;';
        self::assertEquals($sql, $query->dropTable('test')->toSql());
    }

    /**
     * @testdox Mysql show tables form a valid query
     * @group framework
     */
    public function testMysqlShowTables() : void
    {
        $query = new Builder($this->con);
        $sql   = 'SELECT `table_name` FROM `information_schema`.`tables` WHERE `table_schema` = \'' . $GLOBALS['CONFIG']['db']['core']['masters']['admin']['database']. '\';';
        self::assertEquals($sql, $query->selectTables()->toSql());
    }

    /**
     * @testdox Mysql show fields form a valid query
     * @group framework
     */
    public function testMysqlShowFields() : void
    {
        $query = new Builder($this->con);
        $sql   = 'SELECT * FROM `information_schema`.`columns` WHERE `table_schema` = \'' . $GLOBALS['CONFIG']['db']['core']['masters']['admin']['database']. '\' AND `table_name` = \'test\';';
        self::assertEquals($sql, $query->selectFields('test')->toSql());
    }

    /**
     * @testdox Mysql create tables form a valid query
     * @group framework
     */
    public function testMysqlCreateTable() : void
    {
        $query = new Builder($this->con);
        $sql   = 'CREATE TABLE `user_roles` (`user_id` INT NOT NULL AUTO_INCREMENT, `role_id` VARCHAR(10) DEFAULT \'1\' NULL, PRIMARY KEY (`user_id`), FOREIGN KEY (`user_id`) REFERENCES `users` (`ext1_id`), FOREIGN KEY (`role_id`) REFERENCES `roles` (`ext2_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;';
        self::assertEquals(
            $sql,
            $query->createTable('user_roles')
                ->field('user_id', 'INT', null, false, true, false, true, 'users', 'ext1_id')
                ->field('role_id', 'VARCHAR(10)', '1', true, false, false, false, 'roles', 'ext2_id')
            ->toSql()
        );
    }

    public function testMysqlAlter() : void
    {/*
        $query = new Builder($this->con);
        $sql   = 'CREATE TABLE `user_roles` (`user_id` INT NOT NULL AUTO_INCREMENT, `role_id` VARCHAR(10) DEFAULT \'1\' NULL, PRIMARY KEY (`user_id`), FOREIGN KEY (`user_id`) REFERENCES `users` (`ext1_id`), FOREIGN KEY (`role_id`) REFERENCES `roles` (`ext2_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;';
        self::assertEquals(
            $sql,
            $query->createTable('user_roles')
                ->field('user_id', 'INT', null, false, true, false, true, 'users', 'ext1_id')
                ->field('role_id', 'VARCHAR(10)', '1', true, false, false, false, 'roles', 'ext2_id')
            ->toSql()
        );*/
    }

    /**
     * @testdox The grammar correctly deletes a table
     * @covers phpOMS\DataStorage\Database\Schema\Grammar\MysqlGrammar<extended>
     * @group framework
     */
    public function testMysqlCreateFromSchema() : void
    {
        $query = new Builder($this->con);
        $sql   = 'DROP TABLE `test`, `test_foreign`;';

        self::assertEquals(
            $sql,
            $query->dropTable('test')->dropTable('test_foreign')->toSql()
        );
    }
}
