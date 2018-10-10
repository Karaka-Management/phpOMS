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

use phpOMS\DataStorage\Database\Connection\PostgresConnection;
use phpOMS\DataStorage\Database\DatabaseStatus;

class PostgresConnectionTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        if (!extension_loaded('pdo_pgsql')) {
            $this->markTestSkipped(
              'The Postresql extension is not available.'
            );
        }
    }

    public function testConnect()
    {
        $psql = new PostgresConnection($GLOBALS['CONFIG']['db']['core']['postgresql']['admin']);
        self::assertEquals(DatabaseStatus::OK, $psql->getStatus());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['postgresql']['admin']['database'], $psql->getDatabase());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['postgresql']['admin']['host'], $psql->getHost());
        self::assertEquals((int) $GLOBALS['CONFIG']['db']['core']['postgresql']['admin']['port'], $psql->getPort());
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Grammar\PostgresGrammar', $psql->getGrammar());
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidDatabaseType()
    {
        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['db']);
        $psql = new PostgresConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidHost()
    {
        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['host']);
        $psql = new PostgresConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidPort()
    {
        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['port']);
        $psql = new PostgresConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidDatabase()
    {
        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['database']);
        $psql = new PostgresConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidLogin()
    {
        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['login']);
        $psql = new PostgresConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidPassword()
    {
        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        unset($db['password']);
        $psql = new PostgresConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidDatabaseTypeName()
    {
        $db       = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        $db['db'] = 'invalid';
        $psql = new PostgresConnection($db);
    }

    /**
     * todo: apparently this doesn't throw an exception in postgresql?!
     */
    public function testInvalidDatabaseName()
    {
        $db = $GLOBALS['CONFIG']['db']['core']['postgresql']['admin'];
        $db['database'] = 'invalid';

        $mysql = new PostgresConnection($db);
    }
}
