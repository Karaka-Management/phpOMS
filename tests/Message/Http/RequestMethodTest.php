<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Message\Http;

use phpOMS\Message\Http\RequestMethod;

/**
 * @internal
 */
final class RequestMethodTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(6, RequestMethod::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(RequestMethod::getConstants(), \array_unique(RequestMethod::getConstants()));
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals('GET', RequestMethod::GET);
        self::assertEquals('POST', RequestMethod::POST);
        self::assertEquals('PUT', RequestMethod::PUT);
        self::assertEquals('DELETE', RequestMethod::DELETE);
        self::assertEquals('HEAD', RequestMethod::HEAD);
        self::assertEquals('TRACE', RequestMethod::TRACE);
    }
}
