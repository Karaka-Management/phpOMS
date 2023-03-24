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

namespace phpOMS\tests\Utils\Converter;

use phpOMS\Utils\Converter\Ip;

/**
 * @testdox phpOMS\tests\Utils\Converter\IpTest: IP converter
 *
 * @internal
 */
final class IpTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox An ip can be converted to a float
     * @covers phpOMS\Utils\Converter\Ip
     * @group framework
     */
    public function testIp() : void
    {
        self::assertTrue(\abs(1527532998.0 - Ip::ip2Float('91.12.77.198')) < 1);
    }
}
