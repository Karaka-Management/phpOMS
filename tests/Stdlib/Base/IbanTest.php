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

namespace phpOMS\tests\Stdlib\Base;

use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Stdlib\Base\Iban;

/**
 * @internal
 */
class IbanTest extends \PHPUnit\Framework\TestCase
{
    public function testAttributes() : void
    {
        $iban = new Iban('DE22 6008 0000 0960 0280 00');
        self::assertObjectHasAttribute('iban', $iban);
    }

    public function testMethods() : void
    {
        $strRepresentation = 'DE22 6008 0000 0960 0280 00';
        $iban              = new Iban($strRepresentation);

        self::assertEquals(ISO3166TwoEnum::_DEU, $iban->getCountry());
        self::assertEquals('22', $iban->getChecksum());
        self::assertEquals('60080000', $iban->getBankCode());
        self::assertEquals('0960028000', $iban->getAccount());
        self::assertEquals($strRepresentation, $iban->prettyPrint());
        self::assertEquals($strRepresentation, $iban->serialize());

        $iban->unserialize('dE226008000009600280 00');
        self::assertEquals('DE22 6008 0000 0960 0280 00', $iban->serialize());

        self::assertEquals('', $iban->getAccountType());
        self::assertEquals('', $iban->getBicCode());
        self::assertEquals('', $iban->getBranchCode());
        self::assertEquals('', $iban->getCurrency());
        self::assertEquals('', $iban->getHoldersKennital());
        self::assertEquals('', $iban->getNationalChecksum());
        self::assertEquals('', $iban->getOwnerAccountNumber());
        self::assertEquals(22, $iban->getLength());
    }

    public function testInvalidIbanCountry() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $iban = new Iban('ZZ22 6008 0000 0960 0280 00');
    }

    public function testInvalidIbanLength() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $iban = new Iban('DE22 6008 0000 0960 0280 0');
    }

    public function testInvalidIbanChecksum() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $iban = new Iban('DEA9 6008 0000 0960 0280 00');
    }
}
