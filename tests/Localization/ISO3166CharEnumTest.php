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

use phpOMS\Localization\ISO3166CharEnum;

/**
 * @testdox phpOMS\tests\Localization\ISO3166CharEnumTest: ISO 3166 country codes
 * @internal
 */
final class ISO3166CharEnumTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The ISO 3166 country code enum has the correct format of country codes
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        $ok = true;

        $enum = ISO3166CharEnum::getConstants();

        foreach ($enum as $code) {
            if (\strlen($code) !== 3) {
                $ok = false;
                break;
            }
        }

        self::assertTrue($ok);
    }

    /**
     * @testdox The ISO 3166 enum has only unique values
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(ISO3166CharEnum::getConstants(), \array_unique(ISO3166CharEnum::getConstants()));
    }

    public function testRegion() : void
    {
        $regions = [
            'europe', 'asia', 'america', 'oceania', 'africa', 'eu', 'euro',
            'north-europe', 'south-europe', 'east-europe', 'west-europe',
            'middle-east', 'south-america', 'north-america', 'central-asia',
            'south-asia', 'southeast-asia', 'east-asia', 'west-asia',
            'central-africa', 'east-africa', 'north-africa', 'south-africa',
            'west-africe', 'australia', 'polynesia', 'melanesia', 'antarctica',
        ];

        foreach ($regions as $region) {
            self::assertGreaterThan(0, \count(ISO3166CharEnum::getRegion($region)), 'Failed for ' . $region);
        }
    }
}
