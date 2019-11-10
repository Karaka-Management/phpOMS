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

namespace phpOMS\tests\Localization;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Localization\PhoneEnum;

/**
 * @internal
 */
class PhoneEnumTest extends \PHPUnit\Framework\TestCase
{
    /**
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
