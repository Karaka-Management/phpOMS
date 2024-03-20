<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
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
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Message\Http\HttpRequest::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Message\Http\HttpRequestTest: HttpRequest wrapper for http requests')]
final class HttpRequestTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The request has the expected default values after initialization')]
    public function testDefault() : void
    {
        $request = new HttpRequest();

        $_SERVER['HTTP_USER_AGENT'] = OSType::UNKNOWN . BrowserType::UNKNOWN;

        self::assertEquals('en', $request->header->l11n->language);
        self::assertFalse($request->isMobile());
        self::assertEquals(BrowserType::UNKNOWN, $request->getBrowser());
        self::assertEquals(OSType::UNKNOWN, $request->getOS());
        self::assertEquals('127.0.0.1', $request->getOrigin());
        self::assertFalse(HttpRequest::isHttps());
        self::assertEquals([], $request->getHash());
        self::assertEmpty($request->getBody());
        self::assertEmpty($request->files);
        self::assertEquals(RouteVerb::GET, $request->getRouteVerb());
        self::assertEquals(RequestMethod::GET, $request->getMethod());
        self::assertInstanceOf('\phpOMS\Message\Http\HttpHeader', $request->header);
        self::assertInstanceOf('\phpOMS\Message\Http\HttpRequest', HttpRequest::createFromSuperglobals());
        self::assertEquals('', $request->__toString());
        self::assertFalse($request->hasData('key'));
        self::assertNull($request->getData('key'));
        self::assertEquals('XX', $request->header->l11n->country);
        self::assertEquals('en_US', $request->getLocale());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The OS can be set and returned')]
    public function testOSInputOutput() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'), $l11n = new Localization());

        $request->setOS(OSType::WINDOWS_XP);
        self::assertEquals(OSType::WINDOWS_XP, $request->getOS());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The browser can be set and returned')]
    public function testBrowserTypeInputOutput() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'), $l11n = new Localization());

        $request->setOS(OSType::WINDOWS_XP);
        self::assertEquals(OSType::WINDOWS_XP, $request->getOS());

        $request->setBrowser(BrowserType::EDGE);
        self::assertEquals(BrowserType::EDGE, $request->getBrowser());
        self::assertEquals(['browser' => BrowserType::EDGE, 'os' => OSType::WINDOWS_XP], $request->getRequestInfo());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The request method can be set and returned')]
    public function testRequestMethodInputOutput() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'), $l11n = new Localization());

        $request->setMethod(RequestMethod::PUT);
        self::assertEquals(RequestMethod::PUT, $request->getMethod());
        self::assertEquals(RouteVerb::PUT, $request->getRouteVerb());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The request referer can be returned')]
    public function testRequestRefererOutput() : void
    {
        $request = new HttpRequest(new HttpUri(''), $l11n = new Localization());

        self::assertEquals('', $request->getReferer());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The route verb gets correctly inferred from the request method')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The request is correctly constructed')]
    public function testConstructInputOutput() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'), $l11n = new Localization());

        self::assertEquals('http://www.google.com/test/path', $request->__toString());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The url hashes for the different paths get correctly generated')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Request data can be forcefully overwritten')]
    public function testOverwrite() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'), $l11n = new Localization());

        self::assertTrue($request->setData('key', 'value'));
        self::assertTrue($request->setData('key', 'value2', true));
        self::assertEquals('value2', $request->getData('key'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Request data is not overwritten by default')]
    public function testInvalidOverwrite() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'), $l11n = new Localization());

        self::assertTrue($request->setData('key', 'value'));
        self::assertFalse($request->setData('key', 'value2'));
        self::assertEquals('value', $request->getData('key'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The uri can be changed and returned')]
    public function testUriInputOutput() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'), $l11n = new Localization());

        $request->setUri(new HttpUri('http://www.google.com/test/path2'));
        self::assertEquals('http://www.google.com/test/path2', $request->__toString());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Json data can be read from the request')]
    public function testDataJsonRead() : void
    {
        $request = new HttpRequest();

        $data = [
            1, 2, 3,
            'a' => 'b',
            'b' => [4, 5],
        ];

        $request->setData('abc', \json_encode($data));
        self::assertEquals($data, $request->getDataJson('abc'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('None-existing json data reads return empty data')]
    public function testEmptyDataJsonRead() : void
    {
        $request = new HttpRequest();

        self::assertEquals([], $request->getDataJson('def'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid json data returns empty data')]
    public function testInvalidDataJsonRead() : void
    {
        $request = new HttpRequest();

        $data = [
            "0" => 1, "1" => 2, "2" => 3,
            'a' => 'b',
            'b' => [4, 5],
        ];

        $request->setData('abc', \json_encode($data));
        self::assertEquals($data, $request->getDataJson('abc'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('List data can be read from the request')]
    public function testDataList() : void
    {
        $request = new HttpRequest();

        $data = [
            1, 2, 3,
            'a', 'b',
        ];

        $request->setData('abc', \implode(',', $data));
        self::assertEquals($data, $request->getDataList('abc'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('None-existing list data reads return empty data')]
    public function testEmptyDataList() : void
    {
        $request = new HttpRequest();

        self::assertEquals([], $request->getDataList('def'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Request data can be read with pattern matching')]
    public function testDataLike() : void
    {
        $request = new HttpRequest();

        $data = 'this is a test';

        $request->setData('abcde', $data);
        self::assertEquals(['abcde' => $data], $request->getLike('.*'));
        self::assertEquals(['abcde' => $data], $request->getLike('[a-z]*'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('In case of no pattern matches empty data is returned')]
    public function testInvalidDataLikeMatch() : void
    {
        $request = new HttpRequest();

        $data = 'this is a test';

        $request->setData('abcde', $data);
        self::assertEquals([], $request->getLike('[a-z]*\d'));
        self::assertEquals([], $request->getLike('abcdef'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A request with a path can be correctly casted to a string')]
    public function testToString() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'));
        self::assertEquals('http://www.google.com/test/path', $request->__toString());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A request with a path and manually added data can be correctly casted to a string')]
    public function testToStringData() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'));

        $request->setData('test', 'data');
        $request->setData('test2', 3);
        self::assertEquals('http://www.google.com/test/path?test=data&test2=3', $request->__toString());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A request with a path, query parameters and manually added data can be correctly casted to a string')]
    public function testToStringGetData() : void
    {
        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path?test=var'));
        self::assertEquals('http://www.google.com/test/path?test=var', $request->__toString());

        $request->setData('test', 'data');
        $request->setData('test2', 3);
        self::assertEquals('http://www.google.com/test/path?test=var&test=data&test2=3', $request->__toString());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A rest request can be made from a request and the result can be read')]
    public function testRestRequest() : void
    {
        $request = new HttpRequest(new HttpUri('https://raw.githubusercontent.com/Karaka-Management/Karaka/develop/LICENSE.txt'));
        $request->setMethod(RequestMethod::GET);

        self::assertEquals(
            \file_get_contents(__DIR__ . '/../../../LICENSE.txt'),
           $request->rest()->getBody()
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A request can be made with post data')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A request can be made with json data')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A request can be made with multipart data')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('If no language can be identified en is returned')]
    public function testLanguage() : void
    {
        $request = new HttpRequest(new HttpUri('http://localhost:1234' . $GLOBALS['frameworkpath'] . 'tests/Message/Http/HttpRequestLanguage.php'));
        $request->setMethod(RequestMethod::GET);

        self::assertEquals(
            'en',
            Rest::request($request)->getBody()
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('If no locale can be identified en_US is returned')]
    public function testLocale() : void
    {
        $request = new HttpRequest(new HttpUri('http://localhost:1234' . $GLOBALS['frameworkpath'] . 'tests/Message/Http/HttpRequestLocale.php'));
        $request->setMethod(RequestMethod::GET);

        self::assertEquals(
            'en_US',
            Rest::request($request)->getBody()
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-mobile request is recognized as none-mobile')]
    public function testMobile() : void
    {
        $request = new HttpRequest(new HttpUri('http://localhost:1234' . $GLOBALS['frameworkpath'] . 'tests/Message/Http/HttpRequestMobile.php'));
        $request->setMethod(RequestMethod::GET);

        self::assertEquals(
            (string) false,
            Rest::request($request)->getBody()
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('If the OS type is unknown a unknwon OS type is returned')]
    public function testOS() : void
    {
        $request = new HttpRequest(new HttpUri('http://localhost:1234' . $GLOBALS['frameworkpath'] . 'tests/Message/Http/HttpRequestOS.php'));
        $request->setMethod(RequestMethod::GET);

        self::assertEquals(
            OSType::UNKNOWN,
            Rest::request($request)->getBody()
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('If the browser type is unknown a unknwon browser type is returned')]
    public function testBrowser() : void
    {
        $request = new HttpRequest(new HttpUri('http://localhost:1234' . $GLOBALS['frameworkpath'] . 'tests/Message/Http/HttpRequestBrowser.php'));
        $request->setMethod(RequestMethod::GET);

        self::assertEquals(
            BrowserType::UNKNOWN,
            Rest::request($request)->getBody()
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid https port throws a OutOfRangeException')]
    public function testInvalidHttpsPort() : void
    {
        $this->expectException(\OutOfRangeException::class);

        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'));
        $request->isHttps(-1);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A Invalid route verb throws a Exception')]
    public function testInvalidRouteVerb() : void
    {
        $this->expectException(\Exception::class);

        $request = new HttpRequest(new HttpUri('http://www.google.com/test/path'));
        $request->setMethod('failure');
        $request->getRouteVerb();
    }
}
