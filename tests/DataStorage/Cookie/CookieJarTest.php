<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Cookie;

use phpOMS\DataStorage\Cookie\CookieJar;

/**
 * @testdox phpOMS\tests\DataStorage\Cookie\CookieJar: CookieJar to handle http cookies
 *
 * @internal
 */
final class CookieJarTest extends \PHPUnit\Framework\TestCase
{
    protected CookieJar $jar;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->jar = new CookieJar();
    }

    /**
     * @testdox The cookie jar has the expected default values and functionality after initialization
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertFalse(CookieJar::isLocked());
        self::assertNull($this->jar->get('asd'));
        self::assertFalse($this->jar->delete('abc'));
    }

    /**
     * @testdox Cookie values can be set and returned
     * @group framework
     */
    public function testCookieInputOutput() : void
    {
        self::assertTrue($this->jar->set('test', 'value'));
        self::assertEquals('value', $this->jar->get('test')['value']);

        self::assertTrue($this->jar->set('test2', 'value2', 86400, '/', null, false, true, false));
        self::assertEquals('value2', $this->jar->get('test2')['value']);
    }

    /**
     * @testdox Cookie values cannot be overwritten
     * @group framework
     */
    public function testInvalidOverwrite() : void
    {
        self::assertTrue($this->jar->set('test', 'value'));
        self::assertFalse($this->jar->set('test', 'value', 86400, '/', null, false, true, false));
    }

    /**
     * @testdox Cookie values can be forced to overwrite
     * @group framework
     */
    public function testOverwrite() : void
    {
        self::assertTrue($this->jar->set('test', 'value'));
        self::assertTrue($this->jar->set('test', 'value2', 86400, '/', null, false, true, true));
    }

    /**
     * @testdox Cookie values can be removed
     * @group framework
     */
    public function testRemove() : void
    {
        self::assertTrue($this->jar->set('test', 'value'));
        self::assertTrue($this->jar->remove('test'));
    }

    /**
     * @testdox None-existing cookie values cannot be removed
     * @group framework
     */
    public function testInvalidRemove() : void
    {
        self::assertTrue($this->jar->set('test', 'value'));
        self::assertTrue($this->jar->remove('test'));
        self::assertFalse($this->jar->remove('test'));
    }

    /**
     * @testdox Values cannot be removed from a locked cookie and throws a LockException
     * @group framework
     */
    public function testDeleteLocked() : void
    {
        $this->expectException(\phpOMS\DataStorage\LockException::class);

        self::assertTrue($this->jar->set('test', 'value'));

        CookieJar::lock();
        $this->jar->delete('test');
    }

    /**
     * @testdox A locked cookie cannot be saved and throws a LockException
     * @group framework
     */
    public function testSaveLocked() : void
    {
        $this->expectException(\phpOMS\DataStorage\LockException::class);

        CookieJar::lock();
        $this->jar->save();
    }
}
