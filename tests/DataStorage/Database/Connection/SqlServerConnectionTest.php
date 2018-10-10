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

use phpOMS\DataStorage\Database\Connection\SqlServerConnection;

class SqlServerConnectionTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        if (!extension_loaded('pdo_sqlsrv')) {
            $this->markTestSkipped(
              'The Sqlsrv extension is not available.'
            );
        }
    }

    public function testConnect()
    {
        $psql = new SqlServerConnection($GLOBALS['CONFIG']['db']['core']['mssql']['admin']);
        self::assertEquals(DatabaseStatus::OK, $psql->getStatus());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['mssql']['admin']['database'], $psql->getDatabase());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['mssql']['admin']['host'], $psql->getHost());
        self::assertEquals((int) $GLOBALS['CONFIG']['db']['core']['mssql']['admin']['port'], $psql->getPort());
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Grammar\SqlServerGrammar', $psql->getGrammar());
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidDatabaseType()
    {
        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['db']);
        $psql = new SqlServerConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidHost()
    {
        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['host']);
        $psql = new SqlServerConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidPort()
    {
        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['port']);
        $psql = new SqlServerConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidDatabase()
    {
        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['database']);
        $psql = new SqlServerConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidLogin()
    {
        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['login']);
        $psql = new SqlServerConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidPassword()
    {
        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['password']);
        $psql = new SqlServerConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidDatabaseTypeName()
    {
        $db       = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        $db['db'] = 'invalid';
        $psql = new SqlServerConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidDatabaseName()
    {
        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        $db['database'] = 'invalid';

        $mysql = new SqlServerConnection($db);
    }
}
