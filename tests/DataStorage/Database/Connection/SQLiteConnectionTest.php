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

use phpOMS\DataStorage\Database\Connection\SQLiteConnection;
use phpOMS\DataStorage\Database\DatabaseStatus;
use phpOMS\DataStorage\Database\DatabaseType;

/**
 * @testdox phpOMS\tests\DataStorage\Database\Connection\SQLiteConnectionTest: SQLite connection
 *
 * @internal
 */
final class SQLiteConnectionTest extends \PHPUnit\Framework\TestCase
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
     * @covers \phpOMS\DataStorage\Database\Connection\SQLiteConnection<extended>
     * @group framework
     */
    public function testConnect() : void
    {
        $sqlite = new SQLiteConnection($GLOBALS['CONFIG']['db']['core']['sqlite']['admin']);
        $sqlite->connect();
        self::assertEquals(DatabaseStatus::OK, $sqlite->getStatus());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['sqlite']['admin']['database'], $sqlite->getDatabase());
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Grammar\SQLiteGrammar', $sqlite->getGrammar());
        self::assertEquals(DatabaseType::SQLITE, $sqlite->getType());
        $sqlite->close();
    }

    /**
     * @testdox A missing database type returns a failure
     * @covers \phpOMS\DataStorage\Database\Connection\SQLiteConnection
     * @group framework
     */
    public function testInvalidDatabaseType() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['sqlite']['admin'];
        unset($db['db']);
        $sqlite = new SQLiteConnection($db);
        $sqlite->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $sqlite->getStatus());
        $sqlite->close();
    }

    /**
     * @testdox Valid sqlite connection data result in a valid database connection
     * @covers \phpOMS\DataStorage\Database\Connection\SQLiteConnection<extended>
     * @group framework
     */
    public function testInvalidDatabasePath() : void
    {
        $db             = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        $db['database'] = 'invalid.sqlite';

        $sqlite = new SQLiteConnection($db);
        $sqlite->connect();
        self::assertEquals(DatabaseStatus::MISSING_DATABASE, $sqlite->getStatus());
        $sqlite->close();
    }

    /**
     * @testdox A missing database returns a failure
     * @covers \phpOMS\DataStorage\Database\Connection\SQLiteConnection
     * @group framework
     */
    public function testInvalidDatabase() : void
    {
        $db = $GLOBALS['CONFIG']['db']['core']['sqlite']['admin'];
        unset($db['database']);
        $sqlite = new SQLiteConnection($db);
        $sqlite->connect();
        self::assertEquals(DatabaseStatus::FAILURE, $sqlite->getStatus());
        $sqlite->close();
    }

    /**
     * @testdox A invalid database returns a failure
     * @covers \phpOMS\DataStorage\Database\Connection\SQLiteConnection
     * @group framework
     */
    public function testInvalidDatabaseName() : void
    {
        $db             = $GLOBALS['CONFIG']['db']['core']['masters']['admin'];
        $db['database'] = 'invalid';

        $sqlite = new SQLiteConnection($db);
        $sqlite->connect();
        self::assertEquals(DatabaseStatus::MISSING_DATABASE, $sqlite->getStatus());
        $sqlite->close();
    }

    /*
    public static function tearDownAfterClass() : void
    {
        if (\is_file($GLOBALS['CONFIG']['db']['core']['sqlite']['admin']['database'])) {
            \unlink($GLOBALS['CONFIG']['db']['core']['sqlite']['admin']['database']);
        }
    }
    */
}
