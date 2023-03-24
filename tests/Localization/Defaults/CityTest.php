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

namespace phpOMS\tests\Localization\Defaults;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Localization\Defaults\City;

/**
 * @testdox phpOMS\tests\Localization\Defaults\CityTest: City database model
 *
 * @internal
 */
final class CityTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The model has the expected member variables and default values
     * @covers phpOMS\Localization\Defaults\City
     * @group framework
     */
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
