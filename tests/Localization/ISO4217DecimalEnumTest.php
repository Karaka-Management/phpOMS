<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Localization;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Localization\ISO4217DecimalEnum;

class ISO4217DecimalEnumTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
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
