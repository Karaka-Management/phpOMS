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

namespace phpOMS\tests\Message\Http;

include_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Message\Http\Rest;
use phpOMS\Uri\HttpUri;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Message\Http\Rest::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Message\Http\RestTest: Rest request wrapper')]
final class RestTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A get request successfully returns the expected result')]
    public function testRequest() : void
    {
        $request = new HttpRequest(new HttpUri('https://raw.githubusercontent.com/Karaka-Management/Karaka/develop/LICENSE.txt'));
        $request->setMethod(RequestMethod::GET);

        self::assertEquals(
            \file_get_contents(__DIR__ . '/../../../LICENSE.txt'),
            Rest::request($request)->getBody()
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A post request with data successfully returns the expected result')]
    public function testPost() : void
    {
        $request = new HttpRequest(new HttpUri('https://httpbin.org/post'));
        $request->setMethod(RequestMethod::POST);
        self::assertTrue($request->setData('pdata', 'abc'));
        self::assertEquals('abc', REST::request($request)->getData('form')['pdata'] ?? '');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A put request with data successfully returns the expected result')]
    public function testPut() : void
    {
        $request = new HttpRequest(new HttpUri('https://httpbin.org/put'));
        $request->setMethod(RequestMethod::PUT);
        self::assertTrue($request->setData('pdata', 'abc'));
        self::assertEquals('abc', REST::request($request)->getData('form')['pdata'] ?? '');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A delete request with data successfully returns the expected result')]
    public function testDelete() : void
    {
        $request = new HttpRequest(new HttpUri('https://httpbin.org/delete'));
        $request->setMethod(RequestMethod::DELETE);
        self::assertTrue($request->setData('ddata', 'abc'));
        self::assertEquals('abc', REST::request($request)->getData('form')['ddata'] ?? '');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A get request with data successfully returns the expected result')]
    public function testGet() : void
    {
        $request = new HttpRequest(new HttpUri('https://httpbin.org/get'));
        $request->setMethod(RequestMethod::GET);
        self::assertTrue($request->setData('gdata', 'abc'));
        self::assertEquals('abc', REST::request($request)->getData('args')['gdata'] ?? '');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testJsonRequest() : void
    {
        self::markTestIncomplete();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMultiRequest() : void
    {
        self::markTestIncomplete();
    }
}
