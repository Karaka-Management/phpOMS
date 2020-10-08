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

namespace phpOMS\tests\Message\Http;

use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Message\Http\Rest;
use phpOMS\Uri\HttpUri;

/**
 * @testdox phpOMS\tests\Message\Http\RestTest: Rest request wrapper
 *
 * @internal
 */
class RestTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A get request successfully returns the expected result
     * @covers phpOMS\Message\Http\Rest
     * @group framework
     */
    public function testRequest() : void
    {
        $request = new HttpRequest(new HttpUri('https://raw.githubusercontent.com/Orange-Management/Orange-Management/develop/LICENSE.txt'));
        $request->setMethod(RequestMethod::GET);

        self::assertEquals(
            "The OMS License 1.0\n\nCopyright (c) <Dennis Eichhorn> All Rights Reserved\n\nTHE SOFTWARE IS PROVIDED \"AS IS\", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR\nIMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,\nFITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE\nAUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER\nLIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,\nOUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN\nTHE SOFTWARE.",
            Rest::request($request)->getBody()
        );
    }

    /**
     * @testdox A post request with data successfully returns the expected result
     * @covers phpOMS\Message\Http\Rest
     * @group framework
     */
    public function testPost() : void
    {
        $request = new HttpRequest(new HttpUri('http://httpbin.org/post'));
        $request->setMethod(RequestMethod::POST);
        self::assertTrue($request->setData('pdata', 'abc'));
        self::assertEquals('abc', REST::request($request)->getJsonData()['form']['pdata']);
    }

    /**
     * @testdox A put request with data successfully returns the expected result
     * @covers phpOMS\Message\Http\Rest
     * @group framework
     */
    public function testPut() : void
    {
        $request = new HttpRequest(new HttpUri('http://httpbin.org/put'));
        $request->setMethod(RequestMethod::PUT);
        self::assertTrue($request->setData('pdata', 'abc'));
        self::assertEquals('abc', REST::request($request)->getJsonData()['form']['pdata']);
    }

    /**
     * @testdox A delete request with data successfully returns the expected result
     * @covers phpOMS\Message\Http\Rest
     * @group framework
     */
    public function testDelete() : void
    {
        $request = new HttpRequest(new HttpUri('http://httpbin.org/delete'));
        $request->setMethod(RequestMethod::DELETE);
        self::assertTrue($request->setData('ddata', 'abc'));
        self::assertEquals('abc', REST::request($request)->getJsonData()['form']['ddata']);
    }

    /**
     * @testdox A get request with data successfully returns the expected result
     * @covers phpOMS\Message\Http\Rest
     * @group framework
     */
    public function testGet() : void
    {
        $request = new HttpRequest(new HttpUri('http://httpbin.org/get'));
        $request->setMethod(RequestMethod::GET);
        self::assertTrue($request->setData('gdata', 'abc'));
        self::assertEquals('abc', REST::request($request)->getJsonData()['args']['gdata']);
    }

    /**
     * @covers phpOMS\Message\Http\Rest
     * @group framework
     */
    public function testJsonRequest() : void
    {
        self::markTestIncomplete();
    }

    /**
     * @covers phpOMS\Message\Http\Rest
     * @group framework
     */
    public function testMultiRequest() : void
    {
        self::markTestIncomplete();
    }
}
