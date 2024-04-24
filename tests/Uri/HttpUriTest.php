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

namespace phpOMS\tests\Uri;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Uri\HttpUri;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Uri\HttpUri::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Uri\HttpUriTest: Http uri / url')]
final class HttpUriTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A url can be validated')]
    public function testValidator() : void
    {
        self::assertTrue(HttpUri::isValid('http://www.google.de'));
        self::assertTrue(HttpUri::isValid('http://google.de'));
        self::assertTrue(HttpUri::isValid('https://google.de'));
        self::assertFalse(HttpUri::isValid('https:/google.de'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The http url has the expected default values after initialization')]
    public function testDefault() : void
    {
        $obj = new HttpUri('https://www.google.com/test/path.php?para1=abc&para2=2#frag');

        self::assertEquals('', $obj->pass);
        self::assertEquals('', $obj->user);
        self::assertEquals(80, $obj->port);
        self::assertEquals('', $obj->getUserInfo());
        self::assertEquals('', $obj->getRootPath());
        self::assertEquals(0, $obj->getPathOffset());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The url schema can be parsed correctly from a url and overwritten')]
    public function testSchemeInputOutput() : void
    {
        $obj = new HttpUri('https://www.google.com/test/path.php?para1=abc&para2=2#frag');

        self::assertEquals('https', $obj->scheme);

        $obj->scheme = 'ftp';
        self::assertEquals('ftp', $obj->scheme);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The port can be parsed correctly from a url and overwritten')]
    public function testPortInputOutput() : void
    {
        $obj = new HttpUri('https://www.google.com:21/test/path.php?para1=abc&para2=2#frag');

        self::assertEquals(21, $obj->port);

        $obj->port = 123;
        self::assertEquals(123, $obj->port);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The host can be parsed correctly from a url')]
    public function testHostInputOutput() : void
    {
        $obj = new HttpUri('https://www.google.com/test/path.php?para1=abc&para2=2#frag');

        self::assertEquals('www.google.com', $obj->host);

        $obj->host = '127.0.0.1';
        self::assertEquals('127.0.0.1', $obj->host);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The username can be parsed correctly from a url')]
    public function testUsernameInputOutput() : void
    {
        $obj = new HttpUri('https://username:password@google.com/test/path.php?para1=abc&para2=2#frag');

        self::assertEquals('username', $obj->user);

        $obj->user = 'user';
        self::assertEquals('user', $obj->user);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The password can be parsed correctly from a url')]
    public function testPasswordInputOutput() : void
    {
        $obj = new HttpUri('https://username:password@google.com/test/path.php?para1=abc&para2=2#frag');

        self::assertEquals('password', $obj->pass);

        $obj->pass = 'pass';
        self::assertEquals('pass', $obj->pass);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The base can be parsed correctly from a url')]
    public function testBaseInputOutput() : void
    {
        $obj = new HttpUri('https://www.google.com/test/path.php?para1=abc&para2=2#frag');

        self::assertEquals('https://www.google.com', $obj->getBase());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The url can be turned into a string')]
    public function testStringify() : void
    {
        $obj = new HttpUri($uri = 'https://www.google.com/test/path.php?para1=abc&para2=2#frag');

        self::assertEquals($uri, $obj->__toString());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The authority can be parsed correctly from a url')]
    public function testAuthorityInputOutput() : void
    {
        $obj = new HttpUri('https://www.google.com/test/path.php?para1=abc&para2=2#frag');

        self::assertEquals('www.google.com:80', $obj->getAuthority());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The user info can be parsed correctly from a url')]
    public function testUserinfoInputOutput() : void
    {
        $obj = new HttpUri('https://username:password@google.com/test/path.php?para1=abc&para2=2#frag');

        self::assertEquals('username:password', $obj->getUserInfo());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The root path can be set and returned')]
    public function testRootPathInputOutput() : void
    {
        $obj = new HttpUri('https://www.google.com/test/path.php?para1=abc&para2=2#frag');

        $obj->setRootPath('a');
        self::assertEquals('a', $obj->getRootPath());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A path can be overwritten')]
    public function testPathInputOutput() : void
    {
        $obj = new HttpUri('https://www.google.com/test/path.php?para1=abc&para2=2#frag');

        $obj->setPath('new');
        self::assertEquals('new', $obj->getPath());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The path elements can be parsed from a url and overwritten')]
    public function testPathElementInputOutput() : void
    {
        $obj = new HttpUri('https://www.google.com/test/second/path.php?para1=abc&para2=2#frag');

        self::assertEquals(['test', 'second', 'path'], $obj->getPathElements());

        $obj->setPath('new/test');
        self::assertEquals(['new', 'test'], $obj->getPathElements());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The path offset can be set and returned')]
    public function testPathOffsetInputOutput() : void
    {
        $obj = new HttpUri('https://www.google.com/test/path.php?para1=abc&para2=2#frag');

        $obj->setPathOffset(2);
        self::assertEquals(2, $obj->getPathOffset());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The subdomain can be parsed correctly from a url')]
    public function testSubdmonain() : void
    {
        $obj = new HttpUri('https://www.google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals('www', $obj->getSubdomain());

        $obj = new HttpUri('https://google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals('', $obj->getSubdomain());

        $obj = new HttpUri('https://test.www.google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals('test.www', $obj->getSubdomain());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The query data can be parsed correctly from a url')]
    public function testQueryData() : void
    {
        $obj = new HttpUri('https://www.google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals('para1=abc&para2=2', $obj->getQuery());
        self::assertEquals(['para1' => 'abc', 'para2' => '2'], $obj->getQueryArray());
        self::assertEquals('2', $obj->getQuery('para2'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The fragment data can be parsed correctly from a url')]
    public function testFragment() : void
    {
        $obj = new HttpUri('https://www.google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals('frag', $obj->fragment);

        $obj->fragment = 'frag2';
        self::assertEquals('frag2', $obj->fragment);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The path data can be parsed correctly from a url')]
    public function testPathData() : void
    {
        $obj = new HttpUri('https://www.google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals('/test/path', $obj->getPath());
        self::assertEquals('/test/path?para1=abc&para2=2', $obj->getRoute());
        self::assertEquals('test', $obj->getPathElement(0));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The route can be parsed correctly from a url')]
    public function testRouteInputOutput() : void
    {
        $obj = new HttpUri('https://www.google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals('/test/path?para1=abc&para2=2', $obj->getRoute());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid uri cannot get parsed')]
    public function testInvalidUri() : void
    {
        $obj = new HttpUri('http:///03*l.2/test?abc=d');

        self::assertEquals('', $obj->getPath());
        self::assertEquals('', $obj->pass);
        self::assertEquals('', $obj->user);
        self::assertEquals(80, $obj->port);
        self::assertEquals('', $obj->getUserInfo());
        self::assertEquals('', $obj->getRootPath());
    }
}
