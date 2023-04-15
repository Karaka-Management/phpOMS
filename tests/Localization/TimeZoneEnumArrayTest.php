<?php
/**
 * Karaka
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

use phpOMS\Localization\TimeZoneEnumArray;

/**
 * @testdox phpOMS\tests\Localization\TimeZoneEnumArrayTest: Time zone enum array
 * @internal
 */
final class TimeZoneEnumArrayTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The time zone enum array has only unique values
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(\count(TimeZoneEnumArray::getConstants()), \count(\array_unique(TimeZoneEnumArray::getConstants())));
    }
}
