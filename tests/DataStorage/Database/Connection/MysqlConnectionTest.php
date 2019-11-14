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

use phpOMS\DataStorage\Database\Connection\MysqlConnection;
use phpOMS\DataStorage\Database\DatabaseStatus;

/**
 * @testdox phpOMS\tests\DataStorage\Database\Connection\MysqlConnectionTest: Mysql connection
 *
 * @internal
 */
class MysqlConnectionTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (!\extension_loaded('pdo_mysql')) {
            $this->markTestSkipped(
              'The Mysql extension is not available.'
            );
        }
    }

    /**
     * @testdox Valid mysql connection data result in a valid database connection
     * @covers phpOMS\DataStorage\Database\Connection\MysqlConnection
     */
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
     * @testdox A missing database type throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Database\Connection\MysqlConnection
     */
    public function testInvalidDatabaseType() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        unset($db['db']);

        $mysql = new MysqlConnection($db);
    }

    /**
     * @testdox A missing database host throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Database\Connection\MysqlConnection
     */
    public function testInvalidHost() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        unset($db['host']);

        $mysql = new MysqlConnection($db);
    }

    /**
     * @testdox A missing database port throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Database\Connection\MysqlConnection
     */
    public function testInvalidPort() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        unset($db['port']);

        $mysql = new MysqlConnection($db);
    }

    /**
     * @testdox A missing database throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Database\Connection\MysqlConnection
     */
    public function testInvalidDatabase() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        unset($db['database']);

        $mysql = new MysqlConnection($db);
    }

    /**
     * @testdox A missing database login throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Database\Connection\MysqlConnection
     */
    public function testInvalidLogin() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        unset($db['login']);

        $mysql = new MysqlConnection($db);
    }

    /**
     * @testdox A missing database password throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Database\Connection\MysqlConnection
     */
    public function testInvalidPassword() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        unset($db['password']);

        $mysql = new MysqlConnection($db);
    }

    /**
     * @testdox A invalid database type throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Database\Connection\MysqlConnection
     */
    public function testInvalidDatabaseTypeName() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db       = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        $db['db'] = 'invalid';

        $mysql = new MysqlConnection($db);
    }

    /**
     * @testdox A invalid database throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Database\Connection\MysqlConnection
     */
    public function testInvalidDatabaseName() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        $db['database'] = 'invalid';

        $mysql = new MysqlConnection($db);
    }
}
