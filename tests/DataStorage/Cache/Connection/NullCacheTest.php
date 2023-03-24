<?php
/**
 * Karaka
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

namespace phpOMS\tests\DataStorage\Cache\Connection;

use phpOMS\DataStorage\Cache\CacheStatus;
use phpOMS\DataStorage\Cache\CacheType;
use phpOMS\DataStorage\Cache\Connection\NullCache;

/**
 * @testdox phpOMS\tests\DataStorage\Cache\Connection\NullCacheTest: Null cache connection if no cache is available
 *
 * @internal
 */
final class NullCacheTest extends \PHPUnit\Framework\TestCase
{
    protected NullCache $cache;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->cache = new NullCache([]);
    }

    /**
     * @testdox The default cache has the expected default values after initialization
     * @covers phpOMS\DataStorage\Cache\Connection\NullCache<extended>
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertEquals(CacheType::UNDEFINED, $this->cache->getType());
        self::assertEquals([], $this->cache->stats());
        self::assertEquals(0, $this->cache->getThreshold());
    }

    public function testConnect() : void
    {
        $this->cache->connect([]);
        self::assertEquals(CacheStatus::CLOSED, $this->cache->getStatus());
    }

    public function testSetInputOutput() : void
    {
        $this->cache->set(1, 1);
        self::assertNull($this->cache->get(1));
    }

    public function testAddInputOutput() : void
    {
        self::assertTrue($this->cache->add(1, 1));
        self::assertNull($this->cache->get(1));
    }

    public function testGetLike() : void
    {
        self::assertEquals([], $this->cache->getLike(''));
    }

    public function testIncrement() : void
    {
        $this->cache->increment(1, 1);
        self::assertNull($this->cache->get(1));
    }

    public function testDecrement() : void
    {
        $this->cache->decrement(1, 1);
        self::assertNull($this->cache->get(1));
    }

    public function testReplace() : void
    {
        self::assertTrue($this->cache->replace(1, 1));
    }

    public function testRename() : void
    {
        $this->cache->set(1, 1);
        self::assertTrue($this->cache->rename(1, 2));
        self::assertNull($this->cache->get(2));
    }

    public function testDelete() : void
    {
        self::assertTrue($this->cache->delete(1));
    }

    public function testDeleteLike() : void
    {
        self::assertTrue($this->cache->deleteLike(''));
    }

    public function testFlush() : void
    {
        self::assertTrue($this->cache->flush(1));
    }

    public function testFlushAll() : void
    {
        self::assertTrue($this->cache->flushAll());
    }

    public function testExists() : void
    {
        $this->cache->set(1, 1);
        self::assertFalse($this->cache->exists(1));
    }

    public function testUpdateExpire() : void
    {
        self::assertTrue($this->cache->updateExpire(1));
    }

    public function testStats() : void
    {
        $this->cache->set(1, 1);
        $this->cache->add(2, 2);
        self::assertEquals([], $this->cache->stats());
    }
}
