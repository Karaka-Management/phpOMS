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

namespace phpOMS\tests\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\Connection\MysqlConnection;
use phpOMS\DataStorage\Database\DatabaseStatus;

class MysqlConnectionTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (!extension_loaded('pdo_mysql')) {
            $this->markTestSkipped(
              'The Mysql extension is not available.'
            );
        }
    }

    public function testConnect() : void
    {
        $mysql = new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin']);

        self::assertEquals(DatabaseStatus::OK, $mysql->getStatus());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['masters']['admin']['database'], $mysql->getDatabase());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['masters']['admin']['host'], $mysql->getHost());
        self::assertEquals((int) $GLOBALS['CONFIG']['db']['core']['masters']['admin']['port'], $mysql->getPort());
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Grammar\MysqlGrammar', $mysql->getGrammar());
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidDatabaseType() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        unset($db['db']);

        $mysql = new MysqlConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidHost() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        unset($db['host']);

        $mysql = new MysqlConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidPort() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        unset($db['port']);

        $mysql = new MysqlConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidDatabase() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        unset($db['database']);

        $mysql = new MysqlConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidLogin() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        unset($db['login']);

        $mysql = new MysqlConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidPassword() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        unset($db['password']);

        $mysql = new MysqlConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidDatabaseTypeName() : void
    {
        $db       = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        $db['db'] = 'invalid';

        $mysql = new MysqlConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidDatabaseName() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        $db['database'] = 'invalid';

        $mysql = new MysqlConnection($db);
    }
}
