<?php
/**
 * Karaka
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

use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Message\Http\Rest;
use phpOMS\Uri\HttpUri;

/**
 * @testdox phpOMS\tests\Message\Http\RestTest: Rest request wrapper
 *
 * @internal
 */
final class RestTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A get request successfully returns the expected result
     * @covers phpOMS\Message\Http\Rest
     * @group framework
     */
    public function testRequest() : void
    {
        $request = new HttpRequest(new HttpUri('https://raw.githubusercontent.com/Karaka-Management/Karaka/develop/LICENSE.txt'));
        $request->setMethod(RequestMethod::GET);

        self::assertEquals(
            \file_get_contents(__DIR__ . '/../../../LICENSE.txt'),
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
