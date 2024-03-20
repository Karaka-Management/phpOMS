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

namespace phpOMS\tests\Validation\Network;

use phpOMS\Validation\Network\Ip;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Validation\Network\Ip::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Validation\Network\IpTest: IP validator')]
final class IpTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A ip can be validated')]
    public function testValid() : void
    {
        self::assertTrue(IP::isValid('192.168.178.1'));
        self::assertTrue(IP::isValid('2001:0db8:85a3:0000:0000:8a2e:0370:7334'));
        self::assertFalse(IP::isValid('192.168.178.257'));
        self::assertFalse(IP::isValid('localhost'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A ip4 can be validated')]
    public function testValidIp4() : void
    {
        self::assertTrue(IP::isValidIpv4('192.168.178.1'));
        self::assertFalse(IP::isValidIpv4('2001:0db8:85a3:0000:0000:8a2e:0370:7334'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A ip6 can be validated')]
    public function testValidIp6() : void
    {
        self::assertFalse(IP::isValidIpv6('192.168.178.1'));
        self::assertTrue(IP::isValidIpv6('2001:0db8:85a3:0000:0000:8a2e:0370:7334'));
    }
}
