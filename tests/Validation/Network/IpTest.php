<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
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
 * @internal
 */
class IpTest extends \PHPUnit\Framework\TestCase
{
    public function testValid() : void
    {
        self::assertTrue(IP::isValid('192.168.178.1'));
        self::assertTrue(IP::isValid('2001:0db8:85a3:0000:0000:8a2e:0370:7334'));
        self::assertFalse(IP::isValid('192.168.178.257'));
        self::assertFalse(IP::isValid('localhost'));

        self::assertFalse(IP::isValidIpv6('192.168.178.1'));
        self::assertTrue(IP::isValidIpv6('2001:0db8:85a3:0000:0000:8a2e:0370:7334'));

        self::assertTrue(IP::isValidIpv4('192.168.178.1'));
        self::assertFalse(IP::isValidIpv4('2001:0db8:85a3:0000:0000:8a2e:0370:7334'));
    }
}
