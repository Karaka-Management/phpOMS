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

namespace phpOMS\tests\Utils\Converter;

use phpOMS\Utils\Converter\Ip;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\Converter\Ip::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\Converter\IpTest: IP converter')]
final class IpTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An ip can be converted to a float')]
    public function testIp() : void
    {
        self::assertTrue(\abs(1527532998.0 - Ip::ip2Float('91.12.77.198')) < 1);
    }
}
