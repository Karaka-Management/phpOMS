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

namespace phpOMS\tests\DataStorage\Cache\Connection;

use phpOMS\DataStorage\Cache\Connection\ConnectionFactory;

class ConnectionFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        self::assertInstanceOf(
            \phpOMS\DataStorage\Cache\Connection\FileCache::class,
            ConnectionFactory::create(['type' => 'file', 'path' => 'Cache'])
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidCacheType()
    {
        ConnectionFactory::create(['type' => 'invalid', 'path' => 'Cache']);
    }
}
