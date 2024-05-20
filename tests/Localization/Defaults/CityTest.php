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

namespace phpOMS\tests\Localization\Defaults;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Localization\Defaults\City;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Localization\Defaults\City::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Localization\Defaults\CityTest: City database model')]
final class CityTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The model has the expected member variables and default values')]
    public function testDefaults() : void
    {
        $obj = new City();
        self::assertEquals('', $obj->getName());
        self::assertEquals('', $obj->getCountryCode());
        self::assertEquals('', $obj->getState());
        self::assertEquals(0, $obj->getPostal());
        self::assertEquals(0.0, $obj->getLat());
        self::assertEquals(0.0, $obj->getLong());
    }
}
