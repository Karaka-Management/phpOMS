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

namespace phpOMS\tests\Message\Http;

use phpOMS\Message\Http\BrowserType;

class BrowserTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(12, BrowserType::count());
        self::assertEquals(BrowserType::getConstants(), array_unique(BrowserType::getConstants()));

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
