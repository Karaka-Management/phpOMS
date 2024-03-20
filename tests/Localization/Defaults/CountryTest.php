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

namespace phpOMS\tests\Localization\Defaults;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Localization\Defaults\Country;

/**
 * @testdox phpOMS\tests\Localization\Defaults\CountryTest: Country database model
 *
 * @internal
 */
final class CountryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The model has the expected member variables and default values
     * @covers \phpOMS\Localization\Defaults\Country
     * @group framework
     */
    public function testDefaults() : void
    {
        $obj = new Country();
        self::assertEquals(0, $obj->id);
        self::assertEquals('', $obj->getName());
        self::assertEquals('', $obj->getCode2());
        self::assertEquals('', $obj->getCode3());
        self::assertEquals(0, $obj->getNumeric());
        self::assertEquals('', $obj->getSubdevision());
        self::assertFalse($obj->isDeveloped());
    }
}
