<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\Connection\SqlServerConnection;
use phpOMS\DataStorage\Database\DatabaseStatus;
use phpOMS\DataStorage\Database\DatabaseType;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\DataStorage\Database\Connection\SqlServerConnection::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\DataStorage\Database\Connection\SqlServerConnection::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\DataStorage\Database\Connection\SqlServerConnectionTest: Sqlserver connection')]
final class SqlServerConnectionTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (!\extension_loaded('pdo_sqlsrv')) {
            $this->markTestSkipped(
              'The Sqlsrv extension is not available.'
            );
        }

        $ssql = new SqlServerConnection($GLOBALS['CONFIG']['db']['core']['mssql']['admin']);
        $ssql->connect();
        if ($ssql->getStatus() !== DatabaseStatus::OK) {
            $this->markTestSkipped(
                'The Sqlsrv extension is not available.'
              );
        }
        $ssql->close();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Valid sqlserver connection data result in a valid database connection')]
    public function testConnect() : void
    {
        $ssql = new SqlServerConnection($GLOBALS['CONFIG']['db']['core']['mssql']['admin']);
        $ssql->connect();
        self::assertEquals(DatabaseStatus::OK, $ssql->getStatus());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['mssql']['admin']['database'], $ssql->getDatabase());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['mssql']['admin']['host'], $ssql->getHost());
        self::assertEquals((int) $GLOBALS['CONFIG']['db']['core']['mssql']['admin']['port'], $ssql->getPort());
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Grammar\SqlServerGrammar', $ssql->getGrammar());
        self::assertEquals(DatabaseType::SQLSRV, $ssql->getType());
        $ssql->close();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A missing database type returns a failure')]
    public function testInvalidDatabaseType() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['db']);
        $ssql = new SqlServerConnection($db);
        $ssql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $ssql->getStatus());
        $ssql->close();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A missing database host returns a failure')]
    public function testInvalidHost() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['host']);
        $ssql = new SqlServerConnection($db);
        $ssql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $ssql->getStatus());
        $ssql->close();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A missing database port returns a failure')]
    public function testInvalidPort() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['port']);
        $ssql = new SqlServerConnection($db);
        $ssql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $ssql->getStatus());
        $ssql->close();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A missing database returns a failure')]
    public function testInvalidDatabase() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['database']);
        $ssql = new SqlServerConnection($db);
        $ssql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $ssql->getStatus());
        $ssql->close();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A missing database login returns a failure')]
    public function testInvalidLogin() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['login']);
        $ssql = new SqlServerConnection($db);
        $ssql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $ssql->getStatus());
        $ssql->close();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A missing database password returns a failure')]
    public function testInvalidPassword() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        unset($db['password']);
        $ssql = new SqlServerConnection($db);
        $ssql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $ssql->getStatus());
        $ssql->close();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid database type returns a failure')]
    public function testInvalidDatabaseTypeName() : void
    {
        $db       = $GLOBALS['CONFIG']['db']['core']['mssql']['admin'];
        $db['db'] = 'invalid';
        $ssql     = new SqlServerConnection($db);
        $ssql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $ssql->getStatus());
        $ssql->close();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid database returns a failure')]
    public function testInvalidDatabaseName() : void
    {
        $db['database'] = 'invalid';

        $ssql = new SqlServerConnection($db);
        $ssql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $ssql->getStatus());
        $ssql->close();
    }
}
