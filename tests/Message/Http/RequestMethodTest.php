<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Message\Http;

use phpOMS\Message\Http\RequestMethod;

class RequestMethodTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(6, count(RequestMethod::getConstants()));
        self::assertEquals(RequestMethod::getConstants(), array_unique(RequestMethod::getConstants()));

        self::assertEquals('GET', RequestMethod::GET);
        self::assertEquals('POST', RequestMethod::POST);
        self::assertEquals('PUT', RequestMethod::PUT);
        self::assertEquals('DELETE', RequestMethod::DELETE);
        self::assertEquals('HEAD', RequestMethod::HEAD);
        self::assertEquals('TRACE', RequestMethod::TRACE);
    }
}
