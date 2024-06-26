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

namespace phpOMS\tests\Message\Cli;

use phpOMS\Localization\Localization;
use phpOMS\Message\Cli\CliRequest;
use phpOMS\Message\Http\OSType;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Uri\Argument;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Message\Cli\CliRequest::class)]
final class CliRequestTest extends \PHPUnit\Framework\TestCase
{
    private CliRequest $request;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->request = new CliRequest(new Argument('get:some/test/path'), $l11n = new Localization());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDefault() : void
    {
        $request = new CliRequest();
        self::assertEquals('en', $request->header->l11n->language);
        self::assertEquals(OSType::LINUX, $request->getOS());
        self::assertEquals('127.0.0.1', $request->getOrigin());
        self::assertEmpty($request->getBody());
        self::assertInstanceOf('\phpOMS\Message\Cli\CliHeader', $request->header);
        self::assertEquals('', $request->__toString());
        self::assertFalse($request->hasData('key'));
        self::assertNull($request->getData('key'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testOSInputOutput() : void
    {
        $this->request->setOS(OSType::WINDOWS_XP);
        self::assertEquals(OSType::WINDOWS_XP, $this->request->getOS());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMethodInputOutput() : void
    {
        $this->request->setMethod(RequestMethod::POST);
        self::assertEquals(RequestMethod::POST, $this->request->getMethod());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testInputOutputUriString() : void
    {
        self::assertEquals('get:some/test/path', $this->request->uri->__toString());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The url hashes for the different paths get correctly generated')]
    public function testHashingInputOutput() : void
    {
        $request = new CliRequest(new Argument(':test/path ?para1=abc ?para2=2 #frag'), $l11n = new Localization());

        $request->createRequestHashs(0);
        self::assertEquals([
            'da39a3ee5e6b4b0d3255bfef95601890afd80709',
            'bad739b8689b54074a4cdcacad47a55fc983a47c',
            'd5cfa13ac682d76346844316616866213bfcd4be',
            ], $request->getHash());
        self::assertEquals($l11n, $request->header->l11n);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testInputOutputL11n() : void
    {
        $l11n = new Localization();
        self::assertEquals($l11n, $this->request->header->l11n);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDataInputOutput() : void
    {
        self::assertTrue($this->request->setData('key', 'value'));
        self::assertEquals('value', $this->request->getData('key'));
        self::assertEquals(['key', 'value'], $this->request->getData());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testHasData() : void
    {
        self::assertTrue($this->request->setData('key', 'value'));
        self::assertTrue($this->request->hasData('key'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testInvalidOverwrite() : void
    {
        self::assertTrue($this->request->setData('key', 'value'));
        self::assertFalse($this->request->setData('key', 'value2', false));
        self::assertEquals('value', $this->request->getData('key'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testOverwrite() : void
    {
        self::assertTrue($this->request->setData('key', 'value'));
        self::assertTrue($this->request->setData('key', 'value2', true));
        self::assertEquals('value2', $this->request->getData('key'));
        self::assertEquals(['key', 'value2'], $this->request->getData());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testToString() : void
    {
        $request = new CliRequest(new Argument('get:some/test/path'));
        self::assertEquals('get:some/test/path', $request->__toString());

        $request->setData('test', 'data');
        $request->setData('test2', 3);
        self::assertEquals('get:some/test/path', $request->__toString());

        $request = new CliRequest(new Argument('get:some/test/path?test=var'));
        self::assertEquals('get:some/test/path?test=var', $request->__toString());
    }
}
