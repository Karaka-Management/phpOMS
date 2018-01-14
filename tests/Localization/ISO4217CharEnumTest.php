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

namespace Tests\PHPUnit\phpOMS\Localization;

require_once __DIR__ . '/../../../../phpOMS/Autoloader.php';

use phpOMS\Localization\ISO4217CharEnum;

class ISO4217CharEnumTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        $ok = true;

        $enum = ISO4217CharEnum::getConstants();

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
