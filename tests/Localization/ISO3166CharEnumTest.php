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

use phpOMS\Localization\ISO3166CharEnum;

class ISO3166CharEnumTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        $ok = true;

        $enum = ISO3166CharEnum::getConstants();

        foreach ($enum as $code) {
            if (\strlen($code) !== 3) {
                $ok = false;
                break;
            }
        }

        self::assertTrue($ok);
        self::assertEquals(\count($enum), \count(array_unique($enum)));
    }
}
