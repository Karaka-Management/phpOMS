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

use phpOMS\DataStorage\Database\Connection\SQLiteConnection;
use phpOMS\DataStorage\Database\DatabaseStatus;

class SQLiteConnectionTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        if (!extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped(
              'The SQLite extension is not available.'
            );
        }
    }

    public function testConnect()
    {
        $sqlite = new SQLiteConnection($GLOBALS['CONFIG']['db']['core']['sqlite']['admin']);
        self::assertEquals(DatabaseStatus::OK, $sqlite->getStatus());
        self::assertEquals($GLOBALS['CONFIG']['db']['core']['sqlite']['admin']['database'], $sqlite->getDatabase());
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Grammar\SQLiteGrammar', $sqlite->getGrammar());
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidDatabaseType()
    {
        $db = $GLOBALS['CONFIG']['db']['core']['sqlite']['admin'];
        unset($db['db']);
        $sqlite = new SQLiteConnection($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException
     */
    public function testInvalidDatabase()
    {
        $db = $GLOBALS['CONFIG']['db']['core']['sqlite']['admin'];
        unset($db['database']);
        $sqlite = new SQLiteConnection($db);
    }
}
