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
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database;

use phpOMS\DataStorage\Database\Connection\MysqlConnection;
use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\DataStorage\Database\DatabaseStatus;

/**
 * @internal
 */
class DatabasePoolTest extends \PHPUnit\Framework\TestCase
{
    public function testBasicConnection() : void
    {
        $dbPool = new DatabasePool();
        /** @var array $CONFIG */
        $dbPool->create('core', $GLOBALS['CONFIG']['db']['core']['masters']['admin']);

        self::assertEquals($dbPool->get()->getStatus(), DatabaseStatus::OK);
    }

    public function testGetSet() : void
    {
        $dbPool = new DatabasePool();
        /** @var array $CONFIG */

        self::assertTrue($dbPool->create('core', $GLOBALS['CONFIG']['db']['core']['masters']['admin']));
        self::assertFalse($dbPool->create('core', $GLOBALS['CONFIG']['db']['core']['masters']['admin']));

        self::assertInstanceOf('\phpOMS\DataStorage\Database\Connection\ConnectionAbstract', $dbPool->get());
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Connection\NullConnection', $dbPool->get('doesNotExist'));
        self::assertEquals($dbPool->get('core'), $dbPool->get());

        self::assertFalse($dbPool->remove('cores'));
        self::assertTrue($dbPool->remove('core'));

        self::assertInstanceOf('\phpOMS\DataStorage\Database\Connection\NullConnection', $dbPool->get());

        self::assertTrue($dbPool->add('core', new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin'])));
        self::assertFalse($dbPool->add('core', new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin'])));
    }
}
