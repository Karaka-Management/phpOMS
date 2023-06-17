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

namespace phpOMS\tests\DataStorage\Cache;

use phpOMS\DataStorage\Cache\CacheStatus;

/**
 * @internal
 */
final class CacheStatusTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(4, CacheStatus::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(CacheStatus::getConstants(), \array_unique(CacheStatus::getConstants()));
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals(0, CacheStatus::OK);
        self::assertEquals(1, CacheStatus::FAILURE);
        self::assertEquals(2, CacheStatus::READONLY);
        self::assertEquals(3, CacheStatus::CLOSED);
    }
}
