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

namespace phpOMS\tests\Message\Http;

use phpOMS\Localization\Localization;
use phpOMS\Message\Http\BrowserType;
use phpOMS\Message\Http\OSType;
use phpOMS\Message\Http\Request;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Router\RouteVerb;
use phpOMS\Uri\Http;

/**
 * @internal
 */
class RequestTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $request = new Request();

        $_SERVER['HTTP_USER_AGENT'] = OSType::UNKNOWN . BrowserType::UNKNOWN;

        self::assertEquals('en', $request->getHeader()->getL11n()->getLanguage());
        self::assertFalse($request->isMobile());
        self::assertEquals(BrowserType::UNKNOWN, $request->getBrowser());
        self::assertEquals(OSType::UNKNOWN, $request->getOS());
        self::assertEquals('127.0.0.1', $request->getOrigin());
        self::assertFalse($request->isHttps());
        self::assertEquals([], $request->getHash());
        self::assertEmpty($request->getBody());
        self::assertEmpty($request->getFiles());
        self::assertEquals(RouteVerb::GET, $request->getRouteVerb());
        self::assertEquals(RequestMethod::GET, $request->getMethod());
        self::assertInstanceOf('\phpOMS\Message\Http\Header', $request->getHeader());
        self::assertInstanceOf('\phpOMS\Message\Http\Request', Request::createFromSuperglobals());
        self::assertEquals('http://', $request->__toString());
        self::assertFalse($request->hasData('key'));
        self::assertNull($request->getData('key'));
        self::assertEquals('en', $request->getRequestLanguage());
        self::assertEquals('en_US', $request->getLocale());
    }

    public function testSetGet() : void
    {
        $request = new Request(new Http('http://www.google.com/test/path'), $l11n = new Localization());

        $request->setOS(OSType::WINDOWS_XP);
        self::assertEquals(OSType::WINDOWS_XP, $request->getOS());

        $request->setBrowser(BrowserType::EDGE);
        self::assertEquals(BrowserType::EDGE, $request->getBrowser());
        self::assertEquals(['browser' => BrowserType::EDGE, 'os' => OSType::WINDOWS_XP], $request->getRequestInfo());

        $request->setMethod(RequestMethod::PUT);
        self::assertEquals(RequestMethod::PUT, $request->getMethod());
        self::assertEquals(RouteVerb::PUT, $request->getRouteVerb());

        $request->setMethod(RequestMethod::DELETE);
        self::assertEquals(RequestMethod::DELETE, $request->getMethod());
        self::assertEquals(RouteVerb::DELETE, $request->getRouteVerb());

        $request->setMethod(RequestMethod::POST);
        self::assertEquals(RequestMethod::POST, $request->getMethod());
        self::assertEquals(RouteVerb::SET, $request->getRouteVerb());

        self::assertEquals('http://www.google.com/test/path', $request->getUri()->__toString());

        $request->createRequestHashs(0);
        self::assertEquals([
            'da39a3ee5e6b4b0d3255bfef95601890afd80709',
            'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3',
            '328413d996ab9b79af9d4098af3a65b885c4ca64',
            ], $request->getHash());
        self::assertEquals($l11n, $request->getHeader()->getL11n());

        self::assertTrue($request->setData('key', 'value'));
        self::assertFalse($request->setData('key', 'value2', false));
        self::assertEquals('value', $request->getData('key'));
        self::assertTrue($request->hasData('key'));
        self::assertEquals(['key' => 'value'], $request->getData());

        $request->setUri(new Http('http://www.google.com/test/path2'));
        $request->createRequestHashs(0);

        self::assertEquals('http://www.google.com/test/path2', $request->__toString());
    }

    public function testDataJson() : void
    {
        $request = new Request(new Http(''));

        $data = [
            1, 2, 3,
            'a' => 'b',
            'b' => [4, 5],
        ];

        $request->setData('abc', \json_encode($data));
        self::assertEquals($data, $request->getDataJson('abc'));
        self::assertEquals([], $request->getDataJson('def'));
    }

    public function testDataList() : void
    {
        $request = new Request(new Http(''));

        $data = [
            1, 2, 3,
            'a', 'b',
        ];

        $request->setData('abc', \implode(',', $data));
        self::assertEquals($data, $request->getDataList('abc'));
        self::assertEquals([], $request->getDataList('def'));
    }

    public function testDataLike() : void
    {
        $request = new Request(new Http(''));

        $data = 'this is a test';

        $request->setData('abcde', $data);
        self::assertEquals(['abcde' => $data], $request->getLike('.*'));
        self::assertEquals(['abcde' => $data], $request->getLike('[a-z]*'));
        self::assertEquals([], $request->getLike('[a-z]*\d'));
        self::assertEquals([], $request->getLike('abcdef'));
    }

    public function testToString() : void
    {
        $request = new Request(new Http('http://www.google.com/test/path'));
        self::assertEquals('http://www.google.com/test/path', $request->__toString());

        $request->setData('test', 'data');
        $request->setData('test2', 3);
        self::assertEquals('http://www.google.com/test/path?test=data&test2=3', $request->__toString());

        $request = new Request(new Http('http://www.google.com/test/path?test=var'));
        self::assertEquals('http://www.google.com/test/path?test=var', $request->__toString());

        $request->setData('test', 'data');
        $request->setData('test2', 3);
        self::assertEquals('http://www.google.com/test/path?test=var&test=data&test2=3', $request->__toString());
    }

    public function testRestRequest() : void
    {
        $request = new Request(new Http('https://raw.githubusercontent.com/Orange-Management/Orange-Management/develop/LICENSE.txt'));
        $request->setMethod(RequestMethod::GET);

        self::assertEquals(
            "The OMS License 1.0\n\nCopyright (c) <Dennis Eichhorn> All Rights Reserved\n\nTHE SOFTWARE IS PROVIDED \"AS IS\", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR\nIMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,\nFITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE\nAUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER\nLIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,\nOUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN\nTHE SOFTWARE.",
           $request->rest()->getBody()
        );
    }

    public function testInvalidHttpsPort() : void
    {
        self::expectException(\OutOfRangeException::class);

        $request = new Request(new Http('http://www.google.com/test/path'));
        $request->isHttps(-1);
    }

    public function testInvalidRouteVerb() : void
    {
        self::expectException(\Exception::class);

        $request = new Request(new Http('http://www.google.com/test/path'));
        $request->setMethod('failure');
        $request->getRouteVerb();
    }
}
