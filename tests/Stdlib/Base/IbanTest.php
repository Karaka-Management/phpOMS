<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Stdlib\Base;

use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Stdlib\Base\Iban;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Stdlib\Base\Iban::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Stdlib\Base\IbanTest: Iban type')]
final class IbanTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A iban can be correctly parsed into its different components')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A iban can be serialized and unserialized')]
    public function testSearialization() : void
    {
        $strRepresentation = 'DE22 6008 0000 0960 0280 00';
        $iban              = new Iban($strRepresentation);

        self::assertEquals($strRepresentation, $iban->prettyPrint());
        self::assertEquals($strRepresentation, $iban->serialize());

        $iban->unserialize('dE226008000009600280 00');
        self::assertEquals('DE22 6008 0000 0960 0280 00', $iban->serialize());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid iban country code throws a InvalidArgumentException')]
    public function testInvalidIbanCountry() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $iban = new Iban('ZZ22 6008 0000 0960 0280 00');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid iban length throws a InvalidArgumentException')]
    public function testInvalidIbanLength() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $iban = new Iban('DE22 6008 0000 0960 0280 0');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid iban checksum throws a InvalidArgumentException')]
    public function testInvalidIbanChecksum() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $iban = new Iban('DEA9 6008 0000 0960 0280 00');
    }
}
