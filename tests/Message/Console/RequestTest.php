<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Message\Console;

use phpOMS\Message\Console\Request;
use phpOMS\Message\Http\OSType;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Localization\Localization;
use phpOMS\Router\RouteVerb;
use phpOMS\Uri\Argument;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        $request = new Request();

        self::assertEquals('en', $request->getHeader()->getL11n()->getLanguage());
        self::assertEquals(OSType::LINUX, $request->getOS());
        self::assertEquals('127.0.0.1', $request->getOrigin());
        self::assertEquals([], $request->getHash());
        self::assertEmpty($request->getBody());
        self::assertEquals(RouteVerb::GET, $request->getRouteVerb());
        self::assertEquals(RequestMethod::GET, $request->getMethod());
        self::assertInstanceOf('\phpOMS\Message\Console\Header', $request->getHeader());
        self::assertEquals('', $request->__toString());
        self::assertFalse($request->hasData('key'));
        self::assertEquals(null, $request->getData('key'));
    }

    public function testSetGet()
    {
        $request = new Request(new Argument('get:some/test/path'), $l11n = new Localization());

        $request->setOS(OSType::WINDOWS_XP);
        self::assertEquals(OSType::WINDOWS_XP, $request->getOS());

        $request->setMethod(RequestMethod::PUT);
        self::assertEquals(RequestMethod::PUT, $request->getMethod());
        self::assertEquals(RouteVerb::PUT, $request->getRouteVerb());

        $request->setMethod(RequestMethod::DELETE);
        self::assertEquals(RequestMethod::DELETE, $request->getMethod());
        self::assertEquals(RouteVerb::DELETE, $request->getRouteVerb());

        $request->setMethod(RequestMethod::POST);
        self::assertEquals(RequestMethod::POST, $request->getMethod());
        self::assertEquals(RouteVerb::SET, $request->getRouteVerb());

        self::assertEquals('get:some/test/path', $request->getUri()->__toString());

        $request->createRequestHashs(0);
        self::assertEquals([
            'eb875812858d27b22cb2b75f992dffadc1b05c66',
            'fa423b369272e7e19b2a5fa4eeba560e74c0d457',
            '3e353695aa5bfa63bc373702a75865b3619c4c96',
            ], $request->getHash());
        self::assertEquals($l11n, $request->getHeader()->getL11n());

        self::assertTrue($request->setData('key', 'value'));
        self::assertFalse($request->setData('key', 'value2', false));
        self::assertEquals('value', $request->getData('key'));
        self::assertTrue($request->hasData('key'));
        self::assertEquals(['key' => 'value'], $request->getData());

        $request->setUri(new Argument('get:some/test/path2'));
        $request->createRequestHashs(0);

        self::assertEquals('get:some/test/path2', $request->__toString());
    }

    public function testToString()
    {
        $request = new Request(new Argument('get:some/test/path'));
        self::assertEquals('get:some/test/path', $request->__toString());

        $request->setData('test', 'data');
        $request->setData('test2', 3);
        self::assertEquals('get:some/test/path', $request->__toString());

        $request = new Request(new Argument('get:some/test/path?test=var'));
        self::assertEquals('get:some/test/path?test=var', $request->__toString());
    }

    /**
     * @expectedException \Exception
     */
    public function testInvalidRouteVerb()
    {
        $request = new Request(new Argument('get:some/test/path'));
        $request->setMethod('failure');
        $request->getRouteVerb();
    }
}
