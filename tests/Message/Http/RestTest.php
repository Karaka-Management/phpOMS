<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Message\Http;

use phpOMS\Message\Http\Rest;
use phpOMS\Message\Http\Request;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Localization\Localization;
use phpOMS\Uri\Http;

class RestTest extends \PHPUnit\Framework\TestCase
{
    public function testRequest()
    {
        $request = new Request(new Http('http://orange-management.de/phpOMS/LICENSE.txt'));
        $request->setMethod(RequestMethod::GET);

        self::assertEquals(
            "The OMS License 1.0\n\nCopyright (c) <Dennis Eichhorn> All Rights Reserved\n\nTHE SOFTWARE IS PROVIDED \"AS IS\", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR\nIMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,\nFITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE\nAUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER\nLIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,\nOUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN\nTHE SOFTWARE.",
            Rest::request($request)
        );
    }

    public function testPost()
    {
        $request = new Request(new Http('http://httpbin.org/post'));
        $request->setMethod(RequestMethod::POST);
        self::assertTrue($request->setData('pdata', 'abc'));
        self::assertEquals('abc', \json_decode(REST::request($request), true)['form']['pdata']);
    }

    public function testPut()
    {
        $request = new Request(new Http('http://httpbin.org/put'));
        $request->setMethod(RequestMethod::PUT);
        self::assertTrue($request->setData('pdata', 'abc'));
        self::assertEquals('abc', \json_decode(REST::request($request), true)['form']['pdata']);
    }

    public function testDelete()
    {
        $request = new Request(new Http('http://httpbin.org/delete'));
        $request->setMethod(RequestMethod::DELETE);
        self::assertTrue($request->setData('ddata', 'abc'));
        self::assertEquals('abc', \json_decode(REST::request($request), true)['form']['ddata']);
    }

    public function testGet()
    {
        $request = new Request(new Http('http://httpbin.org/get'));
        $request->setMethod(RequestMethod::GET);
        self::assertTrue($request->setData('gdata', 'abc'));
        self::assertEquals('abc', \json_decode(REST::request($request), true)['args']['gdata']);
    }
}
