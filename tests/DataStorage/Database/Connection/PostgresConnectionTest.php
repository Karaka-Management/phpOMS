<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);
namespace phpOMS\tests\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\Connection\PostgresConnection;
use phpOMS\DataStorage\Database\DatabaseStatus;
use phpOMS\DataStorage\Database\DatabaseType;

/**
 * @testdox phpOMS\tests\DataStorage\Database\Connection\PostgresConnectionTest: Postgresql connection
 *
 * @internal
 */
class PostgresConnectionTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (!\extension_loaded('pdo_pgsql')) {
            $this->markTestSkipped(
              'The Postresql extension is not available.'
            );
        }
    }

    /**
     * @testdox Valid postgresql connection data result in a valid database connection
     * @covers phpOMS\DataStorage\Database\Connection\PostgresConnection<extended>
     * @group framework
     */
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
    }

    /**
     * @testdox A missing database type returns a failure
     * @covers phpOMS\DataStorage\Database\Connection\PostgresConnection
     * @group framework
     */
    public function testInvalidDatabaseType() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['db']);
        $psql = new PostgresConnection($db);
        $psql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $psql->getStatus());
    }

    /**
     * @testdox A missing database host returns a failure
     * @covers phpOMS\DataStorage\Database\Connection\PostgresConnection
     * @group framework
     */
    public function testInvalidHost() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['host']);
        $psql = new PostgresConnection($db);
        $psql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $psql->getStatus());
    }

    /**
     * @testdox A missing database port returns a failure
     * @covers phpOMS\DataStorage\Database\Connection\PostgresConnection
     * @group framework
     */
    public function testInvalidPort() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['port']);
        $psql = new PostgresConnection($db);
        $psql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $psql->getStatus());
    }

    /**
     * @testdox A missing database returns a failure
     * @covers phpOMS\DataStorage\Database\Connection\PostgresConnection
     * @group framework
     */
    public function testInvalidDatabase() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['database']);
        $psql = new PostgresConnection($db);
        $psql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $psql->getStatus());
    }

    /**
     * @testdox A missing database login returns a failure
     * @covers phpOMS\DataStorage\Database\Connection\PostgresConnection
     * @group framework
     */
    public function testInvalidLogin() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['login']);
        $psql = new PostgresConnection($db);
        $psql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $psql->getStatus());
    }

    /**
     * @testdox A missing database password returns a failure
     * @covers phpOMS\DataStorage\Database\Connection\PostgresConnection
     * @group framework
     */
    public function testInvalidPassword() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['password']);
        $psql = new PostgresConnection($db);
        $psql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $psql->getStatus());
    }

    /**
     * @testdox A invalid database returns a failure
     * @covers phpOMS\DataStorage\Database\Connection\PostgresConnection
     * @group framework
     */
    public function testInvalidDatabaseTypeName() : void
    {
        $db       = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        $db['db'] = 'invalid';
        $psql     = new PostgresConnection($db);
        $psql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $psql->getStatus());
    }
}
