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

use phpOMS\DataStorage\Database\Connection\PostgresConnection;
use phpOMS\DataStorage\Database\DatabaseStatus;

/**
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

    public function testConnect() : void
    {
        $psql = new PostgresConnection($GLOBALS['CONFIG']['db']['core']['postgresql']['admin']);
        self::assertEquals(DatabaseStatus::OK, $psql->getStatus());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['postgresql']['admin']['database'], $psql->getDatabase());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['postgresql']['admin']['host'], $psql->getHost());
        self::assertEquals((int) $GLOBALS['CONFIG']['db']['core']['postgresql']['admin']['port'], $psql->getPort());
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Grammar\PostgresGrammar', $psql->getGrammar());
    }

    public function testInvalidDatabaseType() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['db']);
        $psql = new PostgresConnection($db);
    }

    public function testInvalidHost() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['host']);
        $psql = new PostgresConnection($db);
    }

    public function testInvalidPort() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['port']);
        $psql = new PostgresConnection($db);
    }

    public function testInvalidDatabase() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['database']);
        $psql = new PostgresConnection($db);
    }

    public function testInvalidLogin() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['login']);
        $psql = new PostgresConnection($db);
    }

    public function testInvalidPassword() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['password']);
        $psql = new PostgresConnection($db);
    }

    public function testInvalidDatabaseTypeName() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db       = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        $db['db'] = 'invalid';
        $psql = new PostgresConnection($db);
    }

    /**
     * todo: apparently this doesn't throw an exception in postgresql?!
     */
    public function testInvalidDatabaseName() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        $db['database'] = 'invalid';

        $mysql = new PostgresConnection($db);
    }
}
