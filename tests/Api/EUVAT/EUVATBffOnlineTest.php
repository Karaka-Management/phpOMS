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

use phpOMS\Api\EUVAT\EUVATBffOnline;

/**
 * @testdox phpOMS\tests\Api\EUVAT\EUVATBffOnlineTest: EU VAT German BFF Online validation
 *
 * @internal
 */
final class EUVATBffOnlineTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The BFF Online service can validate a valid VAT ID
     * @covers phpOMS\Api\EUVAT\EUVATBffOnline
     * @group framework
     */
    public function testValidateInvalidId() : void
    {
        $status = EUVATBffOnline::validate('DE123456789', 'DE123456789');

        self::assertEquals(0, $status['status']);
        self::assertEquals('B', $status['vat']);
    }

    public function testValidateQualifiedInvalidId() : void
    {
        $status = EUVATBffOnline::validateQualified('DE123456789', 'DE123456789', 'TestName', 'TestStreet', 'TestCity', 'TestPostcode');

        self::assertEquals(0, $status['status']);
        self::assertEquals('B', $status['vat']);
    }
}
