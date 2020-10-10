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
    private ConsoleRequest $request;

    protected function setUp() : void
    {
        $this->request = new ConsoleRequest(new Argument('get:some/test/path'), $l11n = new Localization());
    }

    /**
     * @covers phpOMS\Message\Console\ConsoleRequest
     * @group framework
     */
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

    /**
     * @covers phpOMS\Message\Console\ConsoleRequest
     * @group framework
     */
    public function testOSInputOutput() : void
    {
        $this->request->setOS(OSType::WINDOWS_XP);
        self::assertEquals(OSType::WINDOWS_XP, $this->request->getOS());
    }

    /**
     * @covers phpOMS\Message\Console\ConsoleRequest
     * @group framework
     */
    public function testMethodInputOutput() : void
    {
        $this->request->setMethod(RequestMethod::POST);
        self::assertEquals(RequestMethod::POST, $this->request->getMethod());
    }

    /**
     * @covers phpOMS\Message\Console\ConsoleRequest
     * @group framework
     */
    public function testInputOutputUriString() : void
    {
        self::assertEquals('get:some/test/path', $this->request->getUri()->__toString());
    }

    /**
     * @covers phpOMS\Message\Console\ConsoleRequest
     * @group framework
     */
    public function testInputOutputL11n() : void
    {
        $l11n = new Localization();
        self::assertEquals($l11n, $this->request->getHeader()->getL11n());
    }

    /**
     * @covers phpOMS\Message\Console\ConsoleRequest
     * @group framework
     */
    public function testDataInputOutput() : void
    {
        self::assertTrue($this->request->setData('key', 'value'));
        self::assertEquals('value', $this->request->getData('key'));
        self::assertEquals(['key' => 'value'], $this->request->getData());
    }

    /**
     * @covers phpOMS\Message\Console\ConsoleRequest
     * @group framework
     */
    public function testHasData() : void
    {
        self::assertTrue($this->request->setData('key', 'value'));
        self::assertTrue($this->request->hasData('key'));
    }

    /**
     * @covers phpOMS\Message\Console\ConsoleRequest
     * @group framework
     */
    public function testInvalidOverwrite() : void
    {
        self::assertTrue($this->request->setData('key', 'value'));
        self::assertFalse($this->request->setData('key', 'value2', false));
        self::assertEquals('value', $this->request->getData('key'));
    }

    /**
     * @covers phpOMS\Message\Console\ConsoleRequest
     * @group framework
     */
    public function testOverwrite() : void
    {
        self::assertTrue($this->request->setData('key', 'value'));
        self::assertTrue($this->request->setData('key', 'value2', true));
        self::assertEquals('value2', $this->request->getData('key'));
        self::assertEquals(['key' => 'value2'], $this->request->getData());
    }

    /**
     * @covers phpOMS\Message\Console\ConsoleRequest
     * @group framework
     */
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
