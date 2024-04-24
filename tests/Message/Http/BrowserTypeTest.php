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

namespace phpOMS\tests\Message\Http;

use phpOMS\Message\Http\BrowserType;

/**
 * @internal
 */
final class BrowserTypeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnumCount() : void
    {
        self::assertCount(12, BrowserType::getConstants());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testUnique() : void
    {
        self::assertEquals(BrowserType::getConstants(), \array_unique(BrowserType::getConstants()));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnums() : void
    {
        self::assertEquals('msie', BrowserType::IE);
        self::assertEquals('edge', BrowserType::EDGE);
        self::assertEquals('firefox', BrowserType::FIREFOX);
        self::assertEquals('safari', BrowserType::SAFARI);
        self::assertEquals('chrome', BrowserType::CHROME);
        self::assertEquals('opera', BrowserType::OPERA);
        self::assertEquals('netscape', BrowserType::NETSCAPE);
        self::assertEquals('maxthon', BrowserType::MAXTHON);
        self::assertEquals('konqueror', BrowserType::KONQUEROR);
        self::assertEquals('mobile', BrowserType::HANDHELD);
        self::assertEquals('blink', BrowserType::BLINK);
        self::assertEquals('unknown', BrowserType::UNKNOWN);
    }
}
