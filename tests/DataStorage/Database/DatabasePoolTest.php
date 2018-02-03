<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\DataStorage\Database;

use phpOMS\DataStorage\Database\Connection\MysqlConnection;
use phpOMS\DataStorage\Database\DatabaseStatus;
use phpOMS\DataStorage\Database\DatabasePool;

class DatabasePoolTest extends \PHPUnit\Framework\TestCase
{
    public function testBasicConnection()
    {
        $dbPool = new DatabasePool();
        /** @var array $CONFIG */
        $dbPool->create('core', $GLOBALS['CONFIG']['db']['core']['masters']['admin']);

        self::assertEquals($dbPool->get()->getStatus(), DatabaseStatus::OK);
    }

    public function testGetSet()
    {
        $dbPool = new DatabasePool();
        /** @var array $CONFIG */

        self::assertTrue($dbPool->create('core', $GLOBALS['CONFIG']['db']['core']['masters']['admin']));
        self::assertFalse($dbPool->create('core', $GLOBALS['CONFIG']['db']['core']['masters']['admin']));

        self::assertInstanceOf('\phpOMS\DataStorage\Database\Connection\ConnectionAbstract', $dbPool->get());
        self::assertNull($dbPool->get('doesNotExist'));
        self::assertEquals($dbPool->get('core'), $dbPool->get());

        self::assertFalse($dbPool->remove('cores'));
        self::assertTrue($dbPool->remove('core'));

        self::assertNull($dbPool->get());

        self::assertTrue($dbPool->add('core', new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin'])));
        self::assertFalse($dbPool->add('core', new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin'])));
    }
}
