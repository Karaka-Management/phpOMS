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

namespace phpOMS\tests\Api\EUVAT;

use phpOMS\Api\EUVAT\EUVATBffOnline;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Api\EUVAT\EUVATBffOnline::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Api\EUVAT\EUVATBffOnlineTest: EU VAT German BFF Online validation')]
final class EUVATBffOnlineTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The BFF Online service can validate a valid VAT ID')]
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
