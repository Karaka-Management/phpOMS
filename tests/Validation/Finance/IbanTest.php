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

namespace phpOMS\tests\Validation\Finance;

use phpOMS\Validation\Finance\Iban;

/**
 * @testdox phpOMS\tests\Validation\Finance\IbanTest: Iban validator
 *
 * @internal
 */
final class IbanTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A iban can be validated
     * @covers phpOMS\Validation\Finance\Iban
     * @group framework
     */
    public function testValidation() : void
    {
        self::assertTrue(Iban::isValid('DE22 6008 0000 0960 0280 00'));
        self::assertFalse(Iban::isValid('DE22 6X08 0000 0960 0280 00'));
    }

    public function testNumeric() : void
    {
        self::assertFalse(Iban::isValid('IL02 0108 s800 0000 2149 431'));
        self::assertTrue(Iban::isValid('IL02 0108 3800 0000 2149 431'));
    }

    public function testZero() : void
    {
        self::assertFalse(Iban::isValid('MU03 MCBL 0901 0000 0187 9025 010U SD'));
        self::assertTrue(Iban::isValid('MU03 MCBL 0901 0000 0187 9025 000U SD'));
    }

    public function testInvalidName() : void
    {
        self::assertFalse(Iban::isValid('QQ22 6008 0000 0960 0280 00'));
    }

    public function testInvalidLength() : void
    {
        self::assertFalse(Iban::isValid('DE22 6008 0000 0960 0280 0'));
    }

    public function testInvalidChecksum() : void
    {
        self::assertFalse(Iban::isValid('DZ22 6118 1111 1961 1281 2211'));
    }
}
