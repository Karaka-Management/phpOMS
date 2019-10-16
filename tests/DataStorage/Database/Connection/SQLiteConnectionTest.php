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

    public function testConnect() : void
    {
        $sqlite = new SQLiteConnection($GLOBALS['CONFIG']['db']['core']['sqlite']['admin']);
        self::assertEquals(DatabaseStatus::OK, $sqlite->getStatus());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['sqlite']['admin']['database'], $sqlite->getDatabase());
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Grammar\SQLiteGrammar', $sqlite->getGrammar());
    }

    public function testInvalidDatabaseType() : void
    {
        self::expectException(\phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['db']['core']['sqlite']['admin'];
        unset($db['db']);
        $sqlite = new SQLiteConnection($db);
    }

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
