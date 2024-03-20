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

use phpOMS\DataStorage\Database\Connection\MysqlConnection;
use phpOMS\DataStorage\Database\DatabaseStatus;
use phpOMS\DataStorage\Database\DatabaseType;

/**
 * @testdox phpOMS\tests\DataStorage\Database\Connection\MysqlConnectionTest: Mysql connection
 *
 * @internal
 */
final class MysqlConnectionTest extends \PHPUnit\Framework\TestCase
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
     * @covers \phpOMS\DataStorage\Database\Connection\MysqlConnection<extended>
     * @group framework
     */
    public function testConnect() : void
    {
        $mysql = new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin']);
        $mysql->connect();

        self::assertEquals(DatabaseStatus::OK, $mysql->getStatus());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['masters']['admin']['database'], $mysql->getDatabase());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['masters']['admin']['host'], $mysql->getHost());
        self::assertEquals((int) $GLOBALS['CONFIG']['db']['core']['masters']['admin']['port'], $mysql->getPort());
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Grammar\MysqlGrammar', $mysql->getGrammar());
        self::assertEquals(DatabaseType::MYSQL, $mysql->getType());
        $mysql->close();
    }

    /**
     * @testdox A missing database type returns a failure
     * @covers \phpOMS\DataStorage\Database\Connection\MysqlConnection
     * @group framework
     */
    public function testInvalidDatabaseType() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        unset($db['db']);

        $mysql = new MysqlConnection($db);
        $mysql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $mysql->getStatus());
        $mysql->close();
    }

    /**
     * @testdox A missing database host returns a failure
     * @covers \phpOMS\DataStorage\Database\Connection\MysqlConnection
     * @group framework
     */
    public function testInvalidHost() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        unset($db['host']);

        $mysql = new MysqlConnection($db);
        $mysql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $mysql->getStatus());
        $mysql->close();
    }

    /**
     * @testdox A missing database port returns a failure
     * @covers \phpOMS\DataStorage\Database\Connection\MysqlConnection
     * @group framework
     */
    public function testInvalidPort() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        unset($db['port']);

        $mysql = new MysqlConnection($db);
        $mysql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $mysql->getStatus());
        $mysql->close();
    }

    /**
     * @testdox A missing database returns a failure
     * @covers \phpOMS\DataStorage\Database\Connection\MysqlConnection
     * @group framework
     */
    public function testInvalidDatabase() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        unset($db['database']);

        $mysql = new MysqlConnection($db);
        $mysql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $mysql->getStatus());
        $mysql->close();
    }

    /**
     * @testdox A missing database login returns a failure
     * @covers \phpOMS\DataStorage\Database\Connection\MysqlConnection
     * @group framework
     */
    public function testInvalidLogin() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        unset($db['login']);

        $mysql = new MysqlConnection($db);
        $mysql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $mysql->getStatus());
        $mysql->close();
    }

    /**
     * @testdox A missing database password returns a failure
     * @covers \phpOMS\DataStorage\Database\Connection\MysqlConnection
     * @group framework
     */
    public function testInvalidPassword() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        unset($db['password']);

        $mysql = new MysqlConnection($db);
        $mysql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $mysql->getStatus());
        $mysql->close();
    }

    /**
     * @testdox A invalid database type returns a failure
     * @covers \phpOMS\DataStorage\Database\Connection\MysqlConnection
     * @group framework
     */
    public function testInvalidDatabaseTypeName() : void
    {
        $db       = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        $db['db'] = 'invalid';

        $mysql = new MysqlConnection($db);
        $mysql->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $mysql->getStatus());
        $mysql->close();
    }

    /**
     * @testdox A invalid database returns a failure
     * @covers \phpOMS\DataStorage\Database\Connection\MysqlConnection
     * @group framework
     */
    public function testInvalidDatabaseName() : void
    {
        $db             = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        $db['database'] = 'invalid';

        $mysql = new MysqlConnection($db);
        $mysql->connect();
        self::assertEquals(DatabaseStatus::MISSING_DATABASE, $mysql->getStatus());
        $mysql->close();
    }
}
