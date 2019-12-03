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

use phpOMS\DataStorage\Database\Connection\SQLiteConnection;
use phpOMS\DataStorage\Database\DatabaseStatus;

/**
 * @testdox phpOMS\tests\DataStorage\Database\Connection\SQLiteConnectionTest: SQLite connection
 *
 * @internal
 */
class SQLiteConnectionTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (!\extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped(
              'The SQLite extension is not available.'
            );
        }
    }

    /**
     * @testdox Valid sqlite connection data result in a valid database connection
     * @covers phpOMS\DataStorage\Database\Connection\SQLiteConnection
     * @group framework
     */
    public function testConnect() : void
    {
        $sqlite = new SQLiteConnection($GLOBALS['CONFIG']['db']['core']['sqlite']['admin']);
        self::assertEquals(DatabaseStatus::OK, $sqlite->getStatus());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['sqlite']['admin']['database'], $sqlite->getDatabase());
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Grammar\SQLiteGrammar', $sqlite->getGrammar());
    }

    /**
     * @testdox A missing database type throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Database\Connection\SQLiteConnection
     * @group framework
     */
    public function testInvalidDatabaseType() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['sqlite']['admin'];
        unset($db['db']);
        $sqlite = new SQLiteConnection($db);
    }

    /**
     * @testdox A missing database throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Database\Connection\SQLiteConnection
     * @group framework
     */
    public function testInvalidDatabase() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['sqlite']['admin'];
        unset($db['database']);
        $sqlite = new SQLiteConnection($db);
    }

    public static function tearDownAfterClass() : void
    {
        if (\file_exists($GLOBALS['CONFIG']['db']['core']['sqlite']['admin']['database'])) {
            \unlink($GLOBALS['CONFIG']['db']['core']['sqlite']['admin']['database']);
        }
    }
}
