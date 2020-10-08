<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Localization\Defaults;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Localization\Defaults\Country;

/**
 * @testdox phpOMS\tests\Localization\Defaults\CountryTest: Country database model
 *
 * @internal
 */
class CountryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The model has the expected member variables and default values
     * @covers phpOMS\Localization\Defaults\Country
     * @group framework
     */
    public function testDefaults() : void
    {
        $obj = new Country();
        self::assertEquals(0, $obj->getId());
        self::assertEquals('', $obj->getName());
        self::assertEquals('', $obj->getCode2());
        self::assertEquals('', $obj->getCode3());
        self::assertEquals(0, $obj->getNumeric());
        self::assertEquals('', $obj->getSubdevision());
    }
}
