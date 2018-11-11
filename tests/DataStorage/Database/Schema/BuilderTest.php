<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\DataStorage\Database\Schema;

use phpOMS\DataStorage\Database\Schema\Builder;

class BuilderTest extends \PHPUnit\Framework\TestCase
{
    protected $con = null;

    protected function setUp()
    {
        $this->con = new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin']);
    }

    public function testMysqlDrop()
    {
        $query = new Builder($this->con);
        $sql   = 'DROP DATABASE `test`;';
        self::assertEquals($sql, $query->drop('test'));
    }

    public function testMysqlShowTables()
    {
        $query = new Builder($this->con);
        $sql   = 'SELECT `table_name` FROM `information_schema`.`tables` WHERE `table_schema` = \'' . $GLOBALS['CONFIG']['db']['core']['masters']['admin']['database']. '\';';
        self::assertEquals($sql, $query->selectTables());
    }

    public function testMysqlShowFields()
    {
        $query = new Builder($this->con);
        $sql   = 'SELECT * FROM `information_schema`.`columns` WHERE `table_schema` = \'' . $GLOBALS['CONFIG']['db']['core']['masters']['admin']['database']. '\' AND t`able_name` = \'test\';';
        self::assertEquals($sql, $query->selectFields('test'));
    }
}
