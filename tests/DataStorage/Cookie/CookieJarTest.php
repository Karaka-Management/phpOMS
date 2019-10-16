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

namespace phpOMS\tests\DataStorage\Cookie;

use phpOMS\DataStorage\Cookie\CookieJar;

/**
 * @internal
 */
class CookieJarTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $jar = new CookieJar();

        self::assertFalse(CookieJar::isLocked());
        self::assertNull($jar->get('asd'));
        self::assertFalse($jar->delete('abc'));
    }

    public function testCookie() : void
    {
        $jar = new CookieJar();

        self::assertTrue($jar->set('test', 'value'));
        self::assertFalse($jar->set('test', 'value', 86400, '/', null, false, true, false));
        self::assertTrue($jar->set('test2', 'value2', 86400, '/', null, false, true, false));
        self::assertTrue($jar->set('test3', 'value3', 86400, '/', null, false, true, false));

        // header already set
        //self::assertTrue($jar->delete('test2'));
        //self::assertFalse($jar->delete('test2'));

        self::assertTrue($jar->remove('test2'));
        self::assertFalse($jar->remove('test2'));
    }

    public function testDeleteLocked() : void
    {
        self::expectException(\phpOMS\DataStorage\LockException::class);

        $jar = new CookieJar();
        self::assertTrue($jar->set('test', 'value'));

        CookieJar::lock();
        $jar->delete('test');
    }

    public function testSaveLocked() : void
    {
        self::expectException(\phpOMS\DataStorage\LockException::class);

        CookieJar::lock();

        $jar = new CookieJar();
        $jar->save();
    }
}
