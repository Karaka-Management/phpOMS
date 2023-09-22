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

use phpOMS\Localization\ISO4217SubUnitEnum;

/**
 * @testdox phpOMS\tests\Localization\ISO4217SubUnitEnumTest: ISO 4217 currency codes
 * @internal
 */
final class ISO4217SubUnitEnumTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The ISO 4217 currency code enum has the correct format of currency sub units
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        $ok = true;

        $enum = ISO4217SubUnitEnum::getConstants();

        foreach ($enum as $code) {
            if ($code < 0 || $code > 10000 || $code % 5 !== 0) {
                $ok = false;
                break;
            }
        }

        self::assertTrue($ok, 'Failed for ' . $code);
    }
}
