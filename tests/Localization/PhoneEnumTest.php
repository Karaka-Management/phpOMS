<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Localization;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Localization\PhoneEnum;

class PhoneEnumTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        $ok = true;

        $countryCodes = PhoneEnum::getConstants();

        foreach ($countryCodes as $code) {
            if (strlen($code) < 0 || $code > 9999) {
                $ok = false;
                break;
            }
        }

        self::assertTrue($ok);
        // phone numbers seem to be not unique (AU, CX)
    }
}