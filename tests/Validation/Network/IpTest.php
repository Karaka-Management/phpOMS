<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Validation\Network;

use phpOMS\Validation\Network\Ip;

/**
 * @testdox phpOMS\tests\Validation\Network\IpTest: IP validator
 *
 * @internal
 */
final class IpTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A ip can be validated
     * @covers phpOMS\Validation\Network\Ip
     * @group framework
     */
    public function testValid() : void
    {
        self::assertTrue(IP::isValid('192.168.178.1'));
        self::assertTrue(IP::isValid('2001:0db8:85a3:0000:0000:8a2e:0370:7334'));
        self::assertFalse(IP::isValid('192.168.178.257'));
        self::assertFalse(IP::isValid('localhost'));
    }

    /**
     * @testdox A ip4 can be validated
     * @covers phpOMS\Validation\Network\Ip
     * @group framework
     */
    public function testValidIp4() : void
    {
        self::assertTrue(IP::isValidIpv4('192.168.178.1'));
        self::assertFalse(IP::isValidIpv4('2001:0db8:85a3:0000:0000:8a2e:0370:7334'));
    }

    /**
     * @testdox A ip6 can be validated
     * @covers phpOMS\Validation\Network\Ip
     * @group framework
     */
    public function testValidIp6() : void
    {
        self::assertFalse(IP::isValidIpv6('192.168.178.1'));
        self::assertTrue(IP::isValidIpv6('2001:0db8:85a3:0000:0000:8a2e:0370:7334'));
    }
}
