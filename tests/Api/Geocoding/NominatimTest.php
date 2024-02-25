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

namespace phpOMS\tests\Api\Geocoding;

use phpOMS\Api\Geocoding\Nominatim;

/**
 * @testdox phpOMS\tests\Api\Geocoding\NominatimTest: EU VAT Vies validation
 *
 * @internal
 */
final class NominatimTest extends \PHPUnit\Framework\TestCase
{
    public function testGeocoding() : void
    {
        self::assertEqualsWithDelta(
            [
                'lat' => 50.3050738,
                'lon' => 8.688465172531158,
            ],
            Nominatim::geocoding('de', 'Rosbach', 'Kirchstraße 33'),
            0.01
        );

        self::assertEqualsWithDelta(
            [
                'lat' => 50.3050738,
                'lon' => 8.688465172531158,
            ],
            Nominatim::geocoding('de', 'Rosbach', 'Kirchstraße 33', '61191'),
            0.01
        );
    }
}
