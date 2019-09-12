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

namespace phpOMS\tests\DataStorage\Cache;

use phpOMS\DataStorage\Cache\CacheType;

/**
 * @internal
 */
class CacheTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(4, CacheType::getConstants());
        self::assertEquals('file', CacheType::FILE);
        self::assertEquals('mem', CacheType::MEMCACHED);
        self::assertEquals('redis', CacheType::REDIS);
        self::assertEquals('na', CacheType::UNDEFINED);
    }
}
