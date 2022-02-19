<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Stdlib\Base;

use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Stdlib\Base\Iban;

/**
 * @testdox phpOMS\tests\Stdlib\Base\IbanTest: Iban type
 *
 * @internal
 */
final class IbanTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A iban can be correctly parsed into its different components
     * @covers phpOMS\Stdlib\Base\Iban
     * @group framework
     */
    public function testInputOutput() : void
    {
        $strRepresentation = 'DE22 6008 0000 0960 0280 00';
        $iban              = new Iban($strRepresentation);

        self::assertEquals(ISO3166TwoEnum::_DEU, $iban->getCountry());
        self::assertEquals('22', $iban->getChecksum());
        self::assertEquals('60080000', $iban->getBankCode());
        self::assertEquals('0960028000', $iban->getAccount());

        self::assertEquals('', $iban->getAccountType());
        self::assertEquals('', $iban->getBicCode());
        self::assertEquals('', $iban->getBranchCode());
        self::assertEquals('', $iban->getCurrency());
        self::assertEquals('', $iban->getHoldersKennital());
        self::assertEquals('', $iban->getNationalChecksum());
        self::assertEquals('', $iban->getOwnerAccountNumber());
        self::assertEquals(22, $iban->getLength());
    }

    /**
     * @testdox A iban can be serialized and unserialized
     * @covers phpOMS\Stdlib\Base\Iban
     * @group framework
     */
    public function testSearialization() : void
    {
        $strRepresentation = 'DE22 6008 0000 0960 0280 00';
        $iban              = new Iban($strRepresentation);

        self::assertEquals($strRepresentation, $iban->prettyPrint());
        self::assertEquals($strRepresentation, $iban->serialize());

        $iban->unserialize('dE226008000009600280 00');
        self::assertEquals('DE22 6008 0000 0960 0280 00', $iban->serialize());
    }

    /**
     * @testdox A invalid iban country code throws a InvalidArgumentException
     * @covers phpOMS\Stdlib\Base\Iban
     * @group framework
     */
    public function testInvalidIbanCountry() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $iban = new Iban('ZZ22 6008 0000 0960 0280 00');
    }

    /**
     * @testdox A invalid iban length throws a InvalidArgumentException
     * @covers phpOMS\Stdlib\Base\Iban
     * @group framework
     */
    public function testInvalidIbanLength() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $iban = new Iban('DE22 6008 0000 0960 0280 0');
    }

    /**
     * @testdox A invalid iban checksum throws a InvalidArgumentException
     * @covers phpOMS\Stdlib\Base\Iban
     * @group framework
     */
    public function testInvalidIbanChecksum() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $iban = new Iban('DEA9 6008 0000 0960 0280 00');
    }
}
