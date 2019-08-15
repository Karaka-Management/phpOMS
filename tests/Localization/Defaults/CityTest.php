<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Localization\Defaults;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Localization\Defaults\City;

/**
 * @internal
 */
class CityTest extends \PHPUnit\Framework\TestCase
{
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
