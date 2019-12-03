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

namespace phpOMS\tests\Uri;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Uri\Http;

/**
 * @testdox phpOMS\tests\Uri\HttpTest: Http uri / url
 *
 * @internal
 */
class HttpTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A url can be validated
     * @covers phpOMS\Uri\Http
     * @group framework
     */
    public function testValidator() : void
    {
        self::assertTrue(Http::isValid('http://www.google.de'));
        self::assertTrue(Http::isValid('http://google.de'));
        self::assertTrue(Http::isValid('https://google.de'));
        self::assertFalse(Http::isValid('https:/google.de'));
    }

    /**
     * @testdox The http url has the expected default values after initialization
     * @covers phpOMS\Uri\Http
     * @group framework
     */
    public function testDefault() : void
    {
        $obj = new Http('https://www.google.com/test/path.php?para1=abc&para2=2#frag');

        self::assertEquals('', $obj->getPass());
        self::assertEquals('', $obj->getUser());
        self::assertEquals(80, $obj->getPort());
        self::assertEquals('', $obj->getUserInfo());
        self::assertEquals('', $obj->getRootPath());
        self::assertEquals(0, $obj->getPathOffset());
    }

    /**
     * @testdox The url schema can be parsed correctly from a url
     * @covers phpOMS\Uri\Http
     * @group framework
     */
    public function testSchemaInputOutput() : void
    {
        $obj = new Http('https://www.google.com/test/path.php?para1=abc&para2=2#frag');

        self::assertEquals('https', $obj->getScheme());
    }

    /**
     * @testdox The host can be parsed correctly from a url
     * @covers phpOMS\Uri\Http
     * @group framework
     */
    public function testHostInputOutput() : void
    {
        $obj = new Http('https://www.google.com/test/path.php?para1=abc&para2=2#frag');

        self::assertEquals('www.google.com', $obj->getHost());
    }

    /**
     * @testdox The username can be parsed correctly from a url
     * @covers phpOMS\Uri\Http
     * @group framework
     */
    public function testUsernameInputOutput() : void
    {
        $obj = new Http('https://username:password@google.com/test/path.php?para1=abc&para2=2#frag');

        self::assertEquals('username', $obj->getUser());
    }

    /**
     * @testdox The password can be parsed correctly from a url
     * @covers phpOMS\Uri\Http
     * @group framework
     */
    public function testPasswordInputOutput() : void
    {
        $obj = new Http('https://username:password@google.com/test/path.php?para1=abc&para2=2#frag');

        self::assertEquals('password', $obj->getPass());
    }

    /**
     * @testdox The base can be parsed correctly from a url
     * @covers phpOMS\Uri\Http
     * @group framework
     */
    public function testBaseInputOutput() : void
    {
        $obj = new Http('https://www.google.com/test/path.php?para1=abc&para2=2#frag');

        self::assertEquals('https://www.google.com', $obj->getBase());
    }

    /**
     * @testdox The url can be turned into a string
     * @covers phpOMS\Uri\Http
     * @group framework
     */
    public function testStringify() : void
    {
        $obj = new Http($uri = 'https://www.google.com/test/path.php?para1=abc&para2=2#frag');

        self::assertEquals($uri, $obj->__toString());
    }

    /**
     * @testdox The authority can be parsed correctly from a url
     * @covers phpOMS\Uri\Http
     * @group framework
     */
    public function testAuthorityInputOutput() : void
    {
        $obj = new Http('https://www.google.com/test/path.php?para1=abc&para2=2#frag');

        self::assertEquals('www.google.com:80', $obj->getAuthority());
    }

    /**
     * @testdox The user info can be parsed correctly from a url
     * @covers phpOMS\Uri\Http
     * @group framework
     */
    public function testUserinfoInputOutput() : void
    {
        $obj = new Http('https://username:password@google.com/test/path.php?para1=abc&para2=2#frag');

        self::assertEquals('username:password', $obj->getUserInfo());
    }

    /**
     * @testdox The root path can be set and returned
     * @covers phpOMS\Uri\Http
     * @group framework
     */
    public function testRootPathInputOutput() : void
    {
        $obj = new Http('https://www.google.com/test/path.php?para1=abc&para2=2#frag');

        $obj->setRootPath('a');
        self::assertEquals('a', $obj->getRootPath());
    }

    /**
     * @testdox The path offset can be set and returned
     * @covers phpOMS\Uri\Http
     * @group framework
     */
    public function testPathOffsetInputOutput() : void
    {
        $obj = new Http('https://www.google.com/test/path.php?para1=abc&para2=2#frag');

        $obj->setPathOffset(2);
        self::assertEquals(2, $obj->getPathOffset());
    }

    /**
     * @testdox The subdomain can be parsed correctly from a url
     * @covers phpOMS\Uri\Http
     * @group framework
     */
    public function testSubdmonain() : void
    {
        $obj = new Http('https://www.google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals('www', $obj->getSubdomain());

        $obj = new Http('https://google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals('', $obj->getSubdomain());

        $obj = new Http('https://test.www.google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals('test.www', $obj->getSubdomain());
    }

    /**
     * @testdox The query data can be parsed correctly from a url
     * @covers phpOMS\Uri\Http
     * @group framework
     */
    public function testQueryData() : void
    {
        $obj = new Http('https://www.google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals('para1=abc&para2=2', $obj->getQuery());
        self::assertEquals(['para1' => 'abc', 'para2' => '2'], $obj->getQueryArray());
        self::assertEquals('2', $obj->getQuery('para2'));
    }

    /**
     * @testdox The fragment data can be parsed correctly from a url
     * @covers phpOMS\Uri\Http
     * @group framework
     */
    public function testFragment() : void
    {
        $obj = new Http('https://www.google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals('frag', $obj->getFragment());
    }

    /**
     * @testdox The path data can be parsed correctly from a url
     * @covers phpOMS\Uri\Http
     * @group framework
     */
    public function testPathData() : void
    {
        $obj = new Http('https://www.google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals('/test/path', $obj->getPath());
        self::assertEquals('/test/path?para1=abc&para2=2', $obj->getRoute());
        self::assertEquals('test', $obj->getPathElement(0));
    }

    /**
     * @testdox The route can be parsed correctly from a url
     * @covers phpOMS\Uri\Http
     * @group framework
     */
    public function testRouteInputOutput() : void
    {
        $obj = new Http('https://www.google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals('/test/path?para1=abc&para2=2', $obj->getRoute());
    }
}
