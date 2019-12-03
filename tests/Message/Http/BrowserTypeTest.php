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

namespace phpOMS\tests\Message\Http;

use phpOMS\Message\Http\BrowserType;

/**
 * @internal
 */
class BrowserTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(12, BrowserType::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(BrowserType::getConstants(), \array_unique(BrowserType::getConstants()));
    }

    /**
     * @group framework
     * @coversNothing
     */
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
