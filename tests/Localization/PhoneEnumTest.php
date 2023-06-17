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

use phpOMS\Localization\PhoneEnum;

/**
 * @testdox phpOMS\tests\Localization\PhoneEnumTest: Country phone codes
 * @internal
 */
final class PhoneEnumTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The phone enum has the correct format of country phone numbers
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        $ok = true;

        $countryCodes = PhoneEnum::getConstants();

        foreach ($countryCodes as $code) {
            if ($code < 0 || $code > 9999) {
                $ok = false;
                break;
            }
        }

        self::assertTrue($ok);
        // phone numbers seem to be not unique (AU, CX)
    }
}
