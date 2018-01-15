<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Localization;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Localization\ISO639x2Enum;

class ISO639x2EnumTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        $ok = true;

        $enum = ISO639x2Enum::getConstants();

        foreach ($enum as $code) {
            if (strlen($code) !== 3) {
                $ok = false;
                break;
            }
        }

        self::assertTrue($ok);
        self::assertEquals(count($enum), count(array_unique($enum)));
    }
}
