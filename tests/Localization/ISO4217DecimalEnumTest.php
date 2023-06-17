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

use phpOMS\Localization\ISO4217DecimalEnum;

/**
 * @testdox phpOMS\tests\Localization\ISO4217DecimalEnumTest: ISO 4217 currency codes
 * @internal
 */
final class ISO4217DecimalEnumTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The ISO 4217 currency code enum has the correct format of currency decimal places
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        $ok = true;

        $enum = ISO4217DecimalEnum::getConstants();

        foreach ($enum as $code) {
            if ($code > 4 || $code < -2) {
                $ok = false;
                break;
            }
        }

        self::assertTrue($ok);
    }
}
