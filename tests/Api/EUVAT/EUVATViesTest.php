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

namespace phpOMS\tests\Api\EUVAT;

use phpOMS\Api\EUVAT\EUVATVies;

/**
 * @testdox phpOMS\tests\Api\EUVAT\EUVATViesTest: EU VAT Vies validation
 *
 * @internal
 */
final class EUVATViesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The VIES service can validate a valid VAT ID
     * @covers phpOMS\Api\EUVAT\EUVATVies
     * @group framework
     */
    public function testValidateInvalidId() : void
    {
        $vies = new EUVATVies();
        $status = $vies->validate('DE123456789');

        self::assertEquals(0, $status['status']);
        self::assertEquals('C', $status['vat']);
    }
}
