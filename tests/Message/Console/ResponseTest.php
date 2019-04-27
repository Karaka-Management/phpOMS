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

namespace phpOMS\tests\Message\Console;

use phpOMS\Localization\Localization;
use phpOMS\Message\Console\Response;

/**
 * @internal
 */
class ResponseTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $response = new Response(new Localization());
        self::assertEquals('', $response->getBody());
        self::assertEquals('', $response->render());
        self::assertEquals([], $response->toArray());
        self::assertInstanceOf('\phpOMS\Localization\Localization', $response->getHeader()->getL11n());
        self::assertInstanceOf('\phpOMS\Message\Console\Header', $response->getHeader());
    }

    public function testSetGet() : void
    {
        $response = new Response(new Localization());

        $response->setResponse(['a' => 1]);
        self::assertTrue($response->remove('a'));
        self::assertFalse($response->remove('a'));
    }
}
