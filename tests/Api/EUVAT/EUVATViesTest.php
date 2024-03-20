<?php
/**
 * Jingga
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
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Api\EUVAT\EUVATVies::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Api\EUVAT\EUVATViesTest: EU VAT Vies validation')]
final class EUVATViesTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The VIES service can validate a valid VAT ID')]
    public function testValidateInvalidId() : void
    {
        $status = EUVATVies::validate('DE123456789');

        self::assertEquals(-1, $status['status']);
        self::assertEquals('B', $status['vat']);
    }

    public function testValidateQualifiedInvalidId() : void
    {
        $status = EUVATVies::validateQualified('DE123456789', 'DE123456789', 'TestName', 'TestStreet', 'TestCity', 'TestPostcode');

        self::assertEquals(-1, $status['status']);
        self::assertEquals('B', $status['vat']);
    }
}
