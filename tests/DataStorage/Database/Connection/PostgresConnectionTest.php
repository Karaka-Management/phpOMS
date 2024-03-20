<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\Connection\PostgresConnection;
use phpOMS\DataStorage\Database\DatabaseStatus;
use phpOMS\DataStorage\Database\DatabaseType;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\DataStorage\Database\Connection\PostgresConnection::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\DataStorage\Database\Connection\PostgresConnection::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\DataStorage\Database\Connection\PostgresConnectionTest: Postgresql connection')]
final class PostgresConnectionTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (!\extension_loaded('pdo_pgsql')) {
            $this->markTestSkipped(
              'The Postresql extension is not available.'
            );
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Valid postgresql connection data result in a valid database connection')]
    public function testConnect() : void
    {
        $psql = new PostgresConnection($GLOBALS['CONFIG']['db']['core']['postgresql']['admin']);
        $psql->connect();

        self::assertEquals(DatabaseStatus::OK, $psql->getStatus());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['postgresql']['admin']['database'], $psql->getDatabase());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['postgresql']['admin']['host'], $psql->getHost());
        self::assertEquals((int) $GLOBALS['CONFIG']['db']['core']['postgresql']['admin']['port'], $psql->getPort());
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Grammar\PostgresGrammar', $psql->getGrammar());
        self::assertEquals(DatabaseType::PGSQL, $psql->getType());
        $psql->close();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A missing database type returns a failure')]
    public function testInvalidDatabaseType() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['db']);
        $psql = new PostgresConnection($db);
        $psql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $psql->getStatus());
        $psql->close();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A missing database host returns a failure')]
    public function testInvalidHost() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['host']);
        $psql = new PostgresConnection($db);
        $psql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $psql->getStatus());
        $psql->close();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A missing database port returns a failure')]
    public function testInvalidPort() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['port']);
        $psql = new PostgresConnection($db);
        $psql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $psql->getStatus());
        $psql->close();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A missing database returns a failure')]
    public function testInvalidDatabase() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['database']);
        $psql = new PostgresConnection($db);
        $psql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $psql->getStatus());
        $psql->close();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A missing database login returns a failure')]
    public function testInvalidLogin() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['login']);
        $psql = new PostgresConnection($db);
        $psql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $psql->getStatus());
        $psql->close();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A missing database password returns a failure')]
    public function testInvalidPassword() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['password']);
        $psql = new PostgresConnection($db);
        $psql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $psql->getStatus());
        $psql->close();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid database returns a failure')]
    public function testInvalidDatabaseTypeName() : void
    {
        $db       = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        $db['db'] = 'invalid';
        $psql     = new PostgresConnection($db);
        $psql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $psql->getStatus());
        $psql->close();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid database returns a failure')]
    public function testInvalidDatabaseName() : void
    {
        $db             = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        $db['database'] = 'invalid';

        $psql = new PostgresConnection($db);
        $psql->connect();
        self::assertEquals(DatabaseStatus::MISSING_DATABASE, $psql->getStatus());
        $psql->close();
    }
}
