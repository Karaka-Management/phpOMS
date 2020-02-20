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

namespace phpOMS\tests\Message\Console;

use phpOMS\Localization\Localization;
use phpOMS\Message\Console\ConsoleRequest;
use phpOMS\Message\Http\OSType;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Uri\Argument;

/**
 * @internal
 */
class ConsoleRequestTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $request = new ConsoleRequest();

        self::assertEquals('en', $request->getHeader()->getL11n()->getLanguage());
        self::assertEquals(OSType::LINUX, $request->getOS());
        self::assertEquals('127.0.0.1', $request->getOrigin());
        self::assertEmpty($request->getBody());
        self::assertInstanceOf('\phpOMS\Message\Console\ConsoleHeader', $request->getHeader());
        self::assertEquals('', $request->__toString());
        self::assertFalse($request->hasData('key'));
        self::assertNull($request->getData('key'));
    }

    public function testSetGet() : void
    {
        $request = new ConsoleRequest(new Argument('get:some/test/path'), $l11n = new Localization());

        $request->setOS(OSType::WINDOWS_XP);
        self::assertEquals(OSType::WINDOWS_XP, $request->getOS());

        $request->setMethod(RequestMethod::PUT);

        $request->setMethod(RequestMethod::DELETE);

        $request->setMethod(RequestMethod::POST);

        self::assertEquals('get:some/test/path', $request->getUri()->__toString());

        self::assertEquals($l11n, $request->getHeader()->getL11n());

        self::assertTrue($request->setData('key', 'value'));
        self::assertFalse($request->setData('key', 'value2', false));
        self::assertEquals('value', $request->getData('key'));
        self::assertTrue($request->hasData('key'));
        self::assertEquals(['key' => 'value'], $request->getData());
    }

    public function testToString() : void
    {
        $request = new ConsoleRequest(new Argument('get:some/test/path'));
        self::assertEquals('get:some/test/path', $request->__toString());

        $request->setData('test', 'data');
        $request->setData('test2', 3);
        self::assertEquals('get:some/test/path', $request->__toString());

        $request = new ConsoleRequest(new Argument('get:some/test/path?test=var'));
        self::assertEquals('get:some/test/path?test=var', $request->__toString());
    }
}
