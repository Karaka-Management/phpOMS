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

namespace phpOMS\tests\Localization;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Localization\ISO3166NumEnum;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Localization\ISO3166NumEnumTest: ISO 3166 country codes')]
final class ISO3166NumEnumTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The ISO 3166 country code enum has the correct format of country codes')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnums() : void
    {
        $ok = true;

        $enum = ISO3166NumEnum::getConstants();

        foreach ($enum as $code) {
            if (\strlen($code) !== 3) {
                $ok = false;
                break;
            }
        }

        self::assertTrue($ok);
    }

    public function testRegion() : void
    {
        $regions = [
            'europe', 'asia', 'america', 'oceania', 'africa', 'eu', 'euro',
            'north-europe', 'south-europe', 'east-europe', 'west-europe',
            'middle-east', 'south-america', 'north-america', 'central-asia',
            'south-asia', 'southeast-asia', 'east-asia', 'west-asia',
            'central-africa', 'east-africa', 'north-africa', 'south-africa',
            'west-africa', 'australia', 'polynesia', 'melanesia', 'antarctica',
        ];

        foreach ($regions as $region) {
            self::assertGreaterThan(0, \count(ISO3166NumEnum::getRegion($region)), 'Failed for ' . $region);
        }
    }
}
