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

namespace phpOMS\tests\Validation\Network;

use phpOMS\Validation\Network\Hostname;

/**
 * @testdox phpOMS\tests\Validation\Network\HostnameTest: Hostname validator
 *
 * @internal
 */
final class HostnameTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A hostname can be validated
     * @covers phpOMS\Validation\Network\Hostname
     * @group framework
     */
    public function testHostnameDomain() : void
    {
        self::assertTrue(Hostname::isValid('test.com'));
        self::assertFalse(Hostname::isValid('http://test.com'));
        self::assertFalse(Hostname::isValid('test.com/test?something=a'));
        self::assertFalse(Hostname::isValid('//somethign/wrong'));
    }

    /**
     * @testdox A IP can be validated as hostname
     * @covers phpOMS\Validation\Network\Hostname
     * @group framework
     */
    public function testHostnameIp() : void
    {
        self::assertTrue(Hostname::isValid('127.0.0.1'));
        self::assertTrue(Hostname::isValid('[2001:0db8:85a3:0000:0000:8a2e:0370:7334]'));
        self::assertFalse(Hostname::isValid('2001:0db8:85a3:0000:0000:8a2e:0370:7334'));
    }
}
