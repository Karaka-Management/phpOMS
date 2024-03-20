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

namespace phpOMS\tests\Validation\Network;

use phpOMS\Validation\Network\Hostname;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Validation\Network\Hostname::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Validation\Network\HostnameTest: Hostname validator')]
final class HostnameTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A hostname can be validated')]
    public function testHostnameDomain() : void
    {
        self::assertTrue(Hostname::isValid('test.com'));
        self::assertFalse(Hostname::isValid('http://test.com'));
        self::assertFalse(Hostname::isValid('test.com/test?something=a'));
        self::assertFalse(Hostname::isValid('//somethign/wrong'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A IP can be validated as hostname')]
    public function testHostnameIp() : void
    {
        self::assertTrue(Hostname::isValid('127.0.0.1'));
        self::assertTrue(Hostname::isValid('[2001:0db8:85a3:0000:0000:8a2e:0370:7334]'));
        self::assertFalse(Hostname::isValid('2001:0db8:85a3:0000:0000:8a2e:0370:7334'));
    }
}
