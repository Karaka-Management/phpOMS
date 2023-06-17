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

use phpOMS\Localization\ISO8601EnumArray;

/**
 * @testdox phpOMS\tests\Localization\ISO8601EnumArrayTest: ISO 8601 date time formats
 * @internal
 */
final class ISO8601EnumArrayTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The ISO 8601 date time format enum has the correct number of date time formats
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(4, ISO8601EnumArray::getConstants());
    }

    /**
     * @testdox The ISO 8601 enum has only unique values
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(ISO8601EnumArray::getConstants(), \array_unique(ISO8601EnumArray::getConstants()));
    }
}
