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
use phpOMS\DataStorage\Database\DatabaseStatus;

class SqlServerConnectionTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (!extension_loaded('pdo_sqlsrv')) {
            $this->markTestSkipped(
              'The Sqlsrv extension is not available.'
            );
        }
    }

    public function testConnect() : void
    {
        $psql = new SqlServerConnection($GLOBALS['CONFIG']['db']['core']['mssql']['admin']);
        self::assertEquals(DatabaseStatus::OK, $psql->getStatus());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['mssql']['admin']['database'], $psql->getDatabase());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['mssql']['admin']['host'], $psql->getHost());
        self::assertEquals((int) $GLOBALS['CONFIG']['db']['core']['mssql']['admin']['port'], $psql->getPort());
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Grammar\SqlServerGrammar', $psql->getGrammar());
    }

    public function testInvalidDatabaseType() : void
    {
        self::expectedException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['db']);
        $psql = new SqlServerConnection($db);
    }

    public function testInvalidHost() : void
    {
        self::expectedException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['host']);
        $psql = new SqlServerConnection($db);
    }

    public function testInvalidPort() : void
    {
        self::expectedException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['port']);
        $psql = new SqlServerConnection($db);
    }

    public function testInvalidDatabase() : void
    {
        self::expectedException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['database']);
        $psql = new SqlServerConnection($db);
    }

    public function testInvalidLogin() : void
    {
        self::expectedException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['login']);
        $psql = new SqlServerConnection($db);
    }

    public function testInvalidPassword() : void
    {
        self::expectedException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['password']);
        $psql = new SqlServerConnection($db);
    }

    public function testInvalidDatabaseTypeName() : void
    {
        self::expectedException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db       = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        $db['db'] = 'invalid';
        $psql = new SqlServerConnection($db);
    }

    public function testInvalidDatabaseName() : void
    {
        self::expectedException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        $db['database'] = 'invalid';

        $mysql = new SqlServerConnection($db);
    }
}
