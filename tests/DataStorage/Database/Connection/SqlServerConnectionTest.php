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

namespace phpOMS\tests\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\Connection\SqlServerConnection;
use phpOMS\DataStorage\Database\DatabaseStatus;

/**
 * @testdox phpOMS\tests\DataStorage\Database\Connection\SqlServerConnectionTest: Sqlserver connection
 *
 * @internal
 */
class SqlServerConnectionTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (!\extension_loaded('pdo_sqlsrv')) {
            $this->markTestSkipped(
              'The Sqlsrv extension is not available.'
            );
        }
    }

    /**
     * @testdox Valid sqlserver connection data result in a valid database connection
     * @covers phpOMS\DataStorage\Database\Connection\SqlServerConnection
     */
    public function testConnect() : void
    {
        $ssql = new SqlServerConnection($GLOBALS['CONFIG']['db']['core']['mssql']['admin']);
        self::assertEquals(DatabaseStatus::OK, $ssql->getStatus());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['mssql']['admin']['database'], $ssql->getDatabase());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['mssql']['admin']['host'], $ssql->getHost());
        self::assertEquals((int) $GLOBALS['CONFIG']['db']['core']['mssql']['admin']['port'], $ssql->getPort());
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Grammar\SqlServerGrammar', $ssql->getGrammar());
    }

    /**
     * @testdox A missing database type throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Database\Connection\SqlServerConnection
     */
    public function testInvalidDatabaseType() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['db']);
        $ssql = new SqlServerConnection($db);
    }

    /**
     * @testdox A missing database host throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Database\Connection\SqlServerConnection
     */
    public function testInvalidHost() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['host']);
        $ssql = new SqlServerConnection($db);
    }

    /**
     * @testdox A missing database port throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Database\Connection\SqlServerConnection
     */
    public function testInvalidPort() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['port']);
        $ssql = new SqlServerConnection($db);
    }

    /**
     * @testdox A missing database throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Database\Connection\SqlServerConnection
     */
    public function testInvalidDatabase() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['database']);
        $ssql = new SqlServerConnection($db);
    }

    /**
     * @testdox A missing database login throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Database\Connection\SqlServerConnection
     */
    public function testInvalidLogin() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['login']);
        $ssql = new SqlServerConnection($db);
    }

    /**
     * @testdox A missing database password throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Database\Connection\SqlServerConnection
     */
    public function testInvalidPassword() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['password']);
        $ssql = new SqlServerConnection($db);
    }

    /**
     * @testdox A invalid database type throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Database\Connection\SqlServerConnection
     */
    public function testInvalidDatabaseTypeName() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db       = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        $db['db'] = 'invalid';
        $ssql = new SqlServerConnection($db);
    }

    /**
     * @testdox A invalid database throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Database\Connection\SqlServerConnection
     */
    public function testInvalidDatabaseName() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        $db['database'] = 'invalid';

        $ssql = new SqlServerConnection($db);
    }
}
