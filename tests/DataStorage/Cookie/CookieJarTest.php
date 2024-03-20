<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Cookie;

use phpOMS\DataStorage\Cookie\CookieJar;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\DataStorage\Cookie\CookieJar: CookieJar to handle http cookies')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The cookie jar has the expected default values and functionality after initialization')]
    public function testDefault() : void
    {
        self::assertFalse(CookieJar::isLocked());
        self::assertNull($this->jar->get('asd'));
        self::assertFalse($this->jar->delete('abc'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Cookie values can be set and returned')]
    public function testCookieInputOutput() : void
    {
        self::assertTrue($this->jar->set('test', 'value'));
        self::assertEquals('value', $this->jar->get('test')['value']);

        self::assertTrue($this->jar->set('test2', 'value2', 86400, '/', null, false, true, false));
        self::assertEquals('value2', $this->jar->get('test2')['value']);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Cookie values cannot be overwritten')]
    public function testInvalidOverwrite() : void
    {
        self::assertTrue($this->jar->set('test', 'value'));
        self::assertFalse($this->jar->set('test', 'value', 86400, '/', null, false, true, false));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Cookie values can be forced to overwrite')]
    public function testOverwrite() : void
    {
        self::assertTrue($this->jar->set('test', 'value'));
        self::assertTrue($this->jar->set('test', 'value2', 86400, '/', null, false, true, true));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Cookie values can be removed')]
    public function testRemove() : void
    {
        self::assertTrue($this->jar->set('test', 'value'));
        self::assertTrue($this->jar->remove('test'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('None-existing cookie values cannot be removed')]
    public function testInvalidRemove() : void
    {
        self::assertTrue($this->jar->set('test', 'value'));
        self::assertTrue($this->jar->remove('test'));
        self::assertFalse($this->jar->remove('test'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Values cannot be removed from a locked cookie and throws a LockException')]
    public function testDeleteLocked() : void
    {
        $this->expectException(\phpOMS\DataStorage\LockException::class);

        self::assertTrue($this->jar->set('test', 'value'));

        CookieJar::lock();
        $this->jar->delete('test');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A locked cookie cannot be saved and throws a LockException')]
    public function testSaveLocked() : void
    {
        $this->expectException(\phpOMS\DataStorage\LockException::class);

        CookieJar::lock();
        $this->jar->save();
    }
}
