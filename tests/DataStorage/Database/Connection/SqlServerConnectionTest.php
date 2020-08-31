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
use phpOMS\DataStorage\Database\DatabaseType;

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
     * @covers phpOMS\DataStorage\Database\Connection\SqlServerConnection<extended>
     * @group framework
     */
    public function testConnect() : void
    {
        $ssql = new SqlServerConnection($GLOBALS['CONFIG']['db']['core']['mssql']['admin']);
        self::assertEquals(DatabaseStatus::OK, $ssql->getStatus());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['mssql']['admin']['database'], $ssql->getDatabase());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['mssql']['admin']['host'], $ssql->getHost());
        self::assertEquals((int) $GLOBALS['CONFIG']['db']['core']['mssql']['admin']['port'], $ssql->getPort());
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Grammar\SqlServerGrammar', $ssql->getGrammar());
        self::assertEquals(DatabaseType::SQLSRV, $ssql->getType());
    }

    /**
     * @testdox A missing database type returns a failure
     * @covers phpOMS\DataStorage\Database\Connection\SqlServerConnection
     * @group framework
     */
    public function testInvalidDatabaseType() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['db']);
        $ssql = new SqlServerConnection($db);
        self::assertEquals(DatabaseStatus::FAILURE, $ssql->getStatus());
    }

    /**
     * @testdox A missing database host returns a failure
     * @covers phpOMS\DataStorage\Database\Connection\SqlServerConnection
     * @group framework
     */
    public function testInvalidHost() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['host']);
        $ssql = new SqlServerConnection($db);
        self::assertEquals(DatabaseStatus::FAILURE, $ssql->getStatus());
    }

    /**
     * @testdox A missing database port returns a failure
     * @covers phpOMS\DataStorage\Database\Connection\SqlServerConnection
     * @group framework
     */
    public function testInvalidPort() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['port']);
        $ssql = new SqlServerConnection($db);
        self::assertEquals(DatabaseStatus::FAILURE, $ssql->getStatus());
    }

    /**
     * @testdox A missing database returns a failure
     * @covers phpOMS\DataStorage\Database\Connection\SqlServerConnection
     * @group framework
     */
    public function testInvalidDatabase() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['database']);
        $ssql = new SqlServerConnection($db);
        self::assertEquals(DatabaseStatus::FAILURE, $ssql->getStatus());
    }

    /**
     * @testdox A missing database login returns a failure
     * @covers phpOMS\DataStorage\Database\Connection\SqlServerConnection
     * @group framework
     */
    public function testInvalidLogin() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['login']);
        $ssql = new SqlServerConnection($db);
        self::assertEquals(DatabaseStatus::FAILURE, $ssql->getStatus());
    }

    /**
     * @testdox A missing database password returns a failure
     * @covers phpOMS\DataStorage\Database\Connection\SqlServerConnection
     * @group framework
     */
    public function testInvalidPassword() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['password']);
        $ssql = new SqlServerConnection($db);
        self::assertEquals(DatabaseStatus::FAILURE, $ssql->getStatus());
    }

    /**
     * @testdox A invalid database type returns a failure
     * @covers phpOMS\DataStorage\Database\Connection\SqlServerConnection
     * @group framework
     */
    public function testInvalidDatabaseTypeName() : void
    {
        $db       = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        $db['db'] = 'invalid';
        $ssql     = new SqlServerConnection($db);
        self::assertEquals(DatabaseStatus::FAILURE, $ssql->getStatus());
    }

    /**
     * @testdox A invalid database returns a failure
     * @covers phpOMS\DataStorage\Database\Connection\SqlServerConnection
     * @group framework
     */
    public function testInvalidDatabaseName() : void
    {
        $db['database'] = 'invalid';

        $ssql = new SqlServerConnection($db);
        self::assertEquals(DatabaseStatus::FAILURE, $ssql->getStatus());
    }
}
