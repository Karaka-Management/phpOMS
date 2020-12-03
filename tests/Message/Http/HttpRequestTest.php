<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Message\Http;

use phpOMS\Localization\Localization;
use phpOMS\Message\Http\BrowserType;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\OSType;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Message\Http\Rest;
use phpOMS\Router\RouteVerb;
use phpOMS\System\MimeType;
use phpOMS\Uri\HttpUri;

/**
 * @testdox phpOMS\tests\Message\Http\HttpRequestTest: HttpRequest wrapper for http requests
 *
 * @internal
 */
class HttpRequestTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The request has the expected default values after initialization
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testDefault() : void
    {
        $request = new HttpRequest();

        $_SERVER['HTTP_USER_AGENT'] = OSType::UNKNOWN . BrowserType::UNKNOWN;

        self::assertEquals('en', $request->getLanguage());
        self::assertFalse($request->isMobile());
        self::assertEquals(BrowserType::UNKNOWN, $request->getBrowser());
        self::assertEquals(OSType::UNKNOWN, $request->getOS());
        self::assertEquals('127.0.0.1', $request->getOrigin());
        self::assertFalse(HttpRequest::isHttps());
        self::assertEquals([], $request->getHash());
        self::assertEmpty($request->getBody());
        self::assertEmpty($request->getFiles());
        self::assertEquals(RouteVerb::GET, $request->getRouteVerb());
        self::assertEquals(RequestMethod::GET, $request->getMethod());
        self::assertInstanceOf('\phpOMS\Message\Http\HttpHeader', $request->header);
        self::assertInstanceOf('\phpOMS\Message\Http\HttpRequest', HttpRequest::createFromSuperglobals());
        self::assertEquals('http://', $request->__toString());
        self::assertFalse($request->hasData('key'));
        self::assertNull($request->getData('key'));
        self::assertEquals('en', $request->getRequestLanguage());
        self::assertEquals('en_US', $request->getLocale());
    }

    /**
     * @testdox The OS can be set and returned
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testOSInputOutput() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'), $l11n = new Localization());

        $request->setOS(OSType::WINDOWS_XP);
        self::assertEquals(OSType::WINDOWS_XP, $request->getOS());
    }

    /**
     * @testdox The browser can be set and returned
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testBrowserTypeInputOutput() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'), $l11n = new Localization());

        $request->setOS(OSType::WINDOWS_XP);
        self::assertEquals(OSType::WINDOWS_XP, $request->getOS());

        $request->setBrowser(BrowserType::EDGE);
        self::assertEquals(BrowserType::EDGE, $request->getBrowser());
        self::assertEquals(['browser' => BrowserType::EDGE, 'os' => OSType::WINDOWS_XP], $request->getRequestInfo());
    }

    /**
     * @testdox The request method can be set and returned
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testRequestMethodInputOutput() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'), $l11n = new Localization());

        $request->setMethod(RequestMethod::PUT);
        self::assertEquals(RequestMethod::PUT, $request->getMethod());
        self::assertEquals(RouteVerb::PUT, $request->getRouteVerb());
    }

    /**
     * @testdox The route verb gets correctly inferred from the request method
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testRequestMethodToRouteVerb() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'), $l11n = new Localization());

        $request->setMethod(RequestMethod::PUT);
        self::assertEquals(RouteVerb::PUT, $request->getRouteVerb());

        $request->setMethod(RequestMethod::DELETE);
        self::assertEquals(RouteVerb::DELETE, $request->getRouteVerb());

        $request->setMethod(RequestMethod::POST);
        self::assertEquals(RouteVerb::SET, $request->getRouteVerb());
    }

    /**
     * @testdox The request is correctly constructed
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testConstructInputOutput() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'), $l11n = new Localization());

        self::assertEquals('http://www.google.com/test/path', $request->__toString());
    }

    /**
     * @testdox The url hashes for the different paths get correctly generated
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testHashingInputOutput() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'), $l11n = new Localization());

        $request->createRequestHashs(0);
        self::assertEquals([
            'da39a3ee5e6b4b0d3255bfef95601890afd80709',
            'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3',
            '328413d996ab9b79af9d4098af3a65b885c4ca64',
            ], $request->getHash());
        self::assertEquals($l11n, $request->header->l11n);
    }

    /**
     * @testdox Request data can be set and returned
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testDataInputOutput() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'), $l11n = new Localization());

        self::assertTrue($request->setData('key', 'value'));
        self::assertEquals('value', $request->getData('key'));
        self::assertTrue($request->hasData('key'));
        self::assertEquals(['key' => 'value'], $request->getData());
    }

    /**
     * @testdox Request data can be forcefully overwritten
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testOverwrite() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'), $l11n = new Localization());

        self::assertTrue($request->setData('key', 'value'));
        self::assertTrue($request->setData('key', 'value2', true));
        self::assertEquals('value2', $request->getData('key'));
    }

    /**
     * @testdox Request data is not overwritten by default
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testInvalidOverwrite() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'), $l11n = new Localization());

        self::assertTrue($request->setData('key', 'value'));
        self::assertFalse($request->setData('key', 'value2'));
        self::assertEquals('value', $request->getData('key'));
    }

    /**
     * @testdox The uri can be changed and returned
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testUriInputOutput() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'), $l11n = new Localization());

        $request->setUri(new HttpUri('http://www.google.com/test/path2'));
        self::assertEquals('http://www.google.com/test/path2', $request->__toString());
    }

    /**
     * @testdox Json data can be read from the request
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testDataJsonRead() : void
    {
        $request = new HttpRequest(new HttpUri(''));

        $data = [
            1, 2, 3,
            'a' => 'b',
            'b' => [4, 5],
        ];

        $request->setData('abc', \json_encode($data));
        self::assertEquals($data, $request->getDataJson('abc'));
    }

    /**
     * @testdox None-existing json data reads return empty data
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testEmptyDataJsonRead() : void
    {
        $request = new HttpRequest(new HttpUri(''));

        self::assertEquals([], $request->getDataJson('def'));
    }

    /**
     * @testdox Invalid json data returns empty data
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testInvalidDataJsonRead() : void
    {
        $request = new HttpRequest(new HttpUri(''));

        $data = [
            1, 2, 3,
            'a' => 'b',
            'b' => [4, 5],
        ];

        $request->setData('abc', \json_encode($data) . ',');
        self::assertEquals([], $request->getDataJson('abc'));
    }

    /**
     * @testdox List data can be read from the request
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testDataList() : void
    {
        $request = new HttpRequest(new HttpUri(''));

        $data = [
            1, 2, 3,
            'a', 'b',
        ];

        $request->setData('abc', \implode(',', $data));
        self::assertEquals($data, $request->getDataList('abc'));
    }

    /**
     * @testdox None-existing list data reads return empty data
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testEmptyDataList() : void
    {
        $request = new HttpRequest(new HttpUri(''));

        self::assertEquals([], $request->getDataList('def'));
    }

    /**
     * @testdox Request data can be read with pattern matching
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testDataLike() : void
    {
        $request = new HttpRequest(new HttpUri(''));

        $data = 'this is a test';

        $request->setData('abcde', $data);
        self::assertEquals(['abcde' => $data], $request->getLike('.*'));
        self::assertEquals(['abcde' => $data], $request->getLike('[a-z]*'));
    }

    /**
     * @testdox In case of no pattern matches empty data is returned
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testInvalidDataLikeMatch() : void
    {
        $request = new HttpRequest(new HttpUri(''));

        $data = 'this is a test';

        $request->setData('abcde', $data);
        self::assertEquals([], $request->getLike('[a-z]*\d'));
        self::assertEquals([], $request->getLike('abcdef'));
    }

    /**
     * @testdox A request with a path can be correctly casted to a string
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testToString() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'));
        self::assertEquals('http://www.google.com/test/path', $request->__toString());
    }

    /**
     * @testdox A request with a path and manually added data can be correctly casted to a string
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testToStringData() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'));

        $request->setData('test', 'data');
        $request->setData('test2', 3);
        self::assertEquals('http://www.google.com/test/path?test=data&test2=3', $request->__toString());
    }

    /**
     * @testdox A request with a path, query parameters and manually added data can be correctly casted to a string
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testToStringGetData() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path?test=var'));
        self::assertEquals('http://www.google.com/test/path?test=var', $request->__toString());

        $request->setData('test', 'data');
        $request->setData('test2', 3);
        self::assertEquals('http://www.google.com/test/path?test=var&test=data&test2=3', $request->__toString());
    }

    /**
     * @testdox A rest request can be made from a request and the result can be read
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testRestRequest() : void
    {
        $request = new HttpRequest(new HttpUri('https://raw.githubusercontent.com/Orange-Management/Orange-Management/develop/LICENSE.txt'));
        $request->setMethod(RequestMethod::GET);

        self::assertEquals(
            "The OMS License 1.0\n\nCopyright (c) <Dennis Eichhorn> All Rights Reserved\n\nTHE SOFTWARE IS PROVIDED \"AS IS\", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR\nIMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,\nFITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE\nAUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER\nLIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,\nOUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN\nTHE SOFTWARE.",
           $request->rest()->getBody()
        );
    }

    /**
     * @testdox A request can be made with post data
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testPostData() : void
    {
        $request = new HttpRequest(new HttpUri('http://localhost:1234' . $GLOBALS['frameworkpath'] . 'tests/Message/Http/HttpRequestPost.php'));
        $request->setMethod(RequestMethod::POST);
        $request->header->set('Content-Type', MimeType::M_POST);
        $request->setData('testKey', 'testValue');

        self::assertEquals(
            \json_encode($request->getData()),
            Rest::request($request)->getBody()
        );
    }

    /**
     * @testdox A request can be made with json data
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testJsonData() : void
    {
        $request = new HttpRequest(new HttpUri('http://localhost:1234' . $GLOBALS['frameworkpath'] . 'tests/Message/Http/HttpRequestPost.php'));
        $request->setMethod(RequestMethod::POST);
        $request->header->set('Content-Type', MimeType::M_JSON);
        $request->setData('testKey', 'testValue');

        self::assertEquals(
            \json_encode($request->getData()),
            Rest::request($request)->getBody()
        );
    }

    /**
     * @testdox A request can be made with multipart data
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testMultipartData() : void
    {
        $request = new HttpRequest(new HttpUri('http://localhost:1234' . $GLOBALS['frameworkpath'] . 'tests/Message/Http/HttpRequestPost.php'));
        $request->setMethod(RequestMethod::POST);
        $request->header->set('Content-Type', MimeType::M_MULT);
        $request->setData('testKey', 'testValue');

        self::assertEquals(
            \json_encode($request->getData()),
            Rest::request($request)->getBody()
        );
    }

    /**
     * @testdox If no language can be identified en is returned
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testLanguage() : void
    {
        $request = new HttpRequest(new HttpUri('http://localhost:1234' . $GLOBALS['frameworkpath'] . 'tests/Message/Http/HttpRequestLanguage.php'));
        $request->setMethod(RequestMethod::GET);

        self::assertEquals(
            'en',
            Rest::request($request)->getBody()
        );
    }

    /**
     * @testdox If no locale can be identified en_US is returned
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testLocale() : void
    {
        $request = new HttpRequest(new HttpUri('http://localhost:1234' . $GLOBALS['frameworkpath'] . 'tests/Message/Http/HttpRequestLocale.php'));
        $request->setMethod(RequestMethod::GET);

        self::assertEquals(
            'en_US',
            Rest::request($request)->getBody()
        );
    }

    /**
     * @testdox A none-mobile request is recognized as none-mobile
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testMobile() : void
    {
        $request = new HttpRequest(new HttpUri('http://localhost:1234' . $GLOBALS['frameworkpath'] . 'tests/Message/Http/HttpRequestMobile.php'));
        $request->setMethod(RequestMethod::GET);

        self::assertEquals(
            (string) false,
            Rest::request($request)->getBody()
        );
    }

    /**
     * @testdox If the OS type is unknown a unknwon OS type is returned
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testOS() : void
    {
        $request = new HttpRequest(new HttpUri('http://localhost:1234' . $GLOBALS['frameworkpath'] . 'tests/Message/Http/HttpRequestOS.php'));
        $request->setMethod(RequestMethod::GET);

        self::assertEquals(
            OSType::UNKNOWN,
            Rest::request($request)->getBody()
        );
    }

    /**
     * @testdox If the browser type is unknown a unknwon browser type is returned
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testBrowser() : void
    {
        $request = new HttpRequest(new HttpUri('http://localhost:1234' . $GLOBALS['frameworkpath'] . 'tests/Message/Http/HttpRequestBrowser.php'));
        $request->setMethod(RequestMethod::GET);

        self::assertEquals(
            BrowserType::UNKNOWN,
            Rest::request($request)->getBody()
        );
    }

    /**
     * @testdox A invalid https port throws a OutOfRangeException
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testInvalidHttpsPort() : void
    {
        $this->expectException(\OutOfRangeException::class);

        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'));
        $request->isHttps(-1);
    }

    /**
     * @testdox A Invalid route verb throws a Exception
     * @covers phpOMS\Message\Http\HttpRequest<extended>
     * @group framework
     */
    public function testInvalidRouteVerb() : void
    {
        $this->expectException(\Exception::class);

        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'));
        $request->setMethod('failure');
        $request->getRouteVerb();
    }
}
