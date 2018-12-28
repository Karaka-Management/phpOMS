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

use phpOMS\Localization\ISO3166TwoEnum;

class ISO3166TwoEnumTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        $ok = true;

        $countryCodes = ISO3166TwoEnum::getConstants();

        foreach ($countryCodes as $code) {
            if (\strlen($code) !== 2) {
                $ok = false;
                break;
            }
        }

        self::assertTrue($ok);
        self::assertEquals(\count($countryCodes), \count(array_unique($countryCodes)));
    }
}
