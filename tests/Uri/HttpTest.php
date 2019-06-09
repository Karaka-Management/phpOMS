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
 declare(strict_types=1);

namespace phpOMS\tests\Uri;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Uri\Http;

/**
 * @internal
 */
class HttpTest extends \PHPUnit\Framework\TestCase
{
    public function testAttributes() : void
    {
        $obj = new Http('');

        /* Testing members */
        self::assertObjectHasAttribute('rootPath', $obj);
        self::assertObjectHasAttribute('uri', $obj);
        self::assertObjectHasAttribute('scheme', $obj);
        self::assertObjectHasAttribute('host', $obj);
        self::assertObjectHasAttribute('port', $obj);
        self::assertObjectHasAttribute('user', $obj);
        self::assertObjectHasAttribute('pass', $obj);
        self::assertObjectHasAttribute('path', $obj);
        self::assertObjectHasAttribute('query', $obj);
        self::assertObjectHasAttribute('queryString', $obj);
        self::assertObjectHasAttribute('fragment', $obj);
        self::assertObjectHasAttribute('base', $obj);
    }

    public function testHelper() : void
    {
        self::assertTrue(Http::isValid('http://www.google.de'));
        self::assertTrue(Http::isValid('http://google.de'));
        self::assertTrue(Http::isValid('https://google.de'));
        self::assertFalse(Http::isValid('https:/google.de'));
    }

    public function testGeneralUriComponents() : void
    {
        $obj = new Http($uri = 'https://www.google.com/test/path.php?para1=abc&para2=2#frag');

        self::assertEquals('https', $obj->getScheme());
        self::assertEquals('www.google.com', $obj->getHost());
        self::assertEquals(80, $obj->getPort());
        self::assertEquals('', $obj->getPass());
        self::assertEquals('', $obj->getUser());
        self::assertEquals('https://www.google.com', $obj->getBase());
        self::assertEquals($uri, $obj->__toString());
        self::assertEquals('www.google.com:80', $obj->getAuthority());
        self::assertEquals('', $obj->getUserInfo());
    }

    public function testRootPath() : void
    {
        $obj = new Http('https://www.google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals('', $obj->getRootPath());

        $obj->setRootPath('a');
        self::assertEquals('a', $obj->getRootPath());
    }

    public function testPathOffset() : void
    {
        $obj = new Http('https://www.google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals(0, $obj->getPathOffset());

        $obj->setPathOffset(2);
        self::assertEquals(2, $obj->getPathOffset());
    }

    public function testSubdmonain() : void
    {
        $obj = new Http('https://www.google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals('www', $obj->getSubmdomain());

        $obj = new Http('https://google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals('', $obj->getSubmdomain());

        $obj = new Http('https://test.www.google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals('test.www', $obj->getSubmdomain());
    }

    public function testQueryData() : void
    {
        $obj = new Http('https://www.google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals('para1=abc&para2=2', $obj->getQuery());
        self::assertEquals(['para1' => 'abc', 'para2' => '2'], $obj->getQueryArray());
        self::assertEquals('2', $obj->getQuery('para2'));
        self::assertEquals('frag', $obj->getFragment());
    }

    public function testPathData() : void
    {
        $obj = new Http('https://www.google.com/test/path.php?para1=abc&para2=2#frag');
        self::assertEquals('/test/path', $obj->getPath());
        self::assertEquals('/test/path?para1=abc&para2=2', $obj->getRoute());
        self::assertEquals('test', $obj->getPathElement(0));
    }
}
