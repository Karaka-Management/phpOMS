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

use phpOMS\Localization\TimeZoneEnumArray;

class TimeZoneEnumArrayTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertEquals(\count(TimeZoneEnumArray::getConstants()), \count(\array_unique(TimeZoneEnumArray::getConstants())));
    }
}
