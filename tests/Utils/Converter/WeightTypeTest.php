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

namespace phpOMS\tests\Utils\Converter;

use phpOMS\Utils\Converter\WeightType;

/**
 * @internal
 */
class WeightTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(14, WeightType::getConstants());
        self::assertEquals(WeightType::getConstants(), \array_unique(WeightType::getConstants()));

        self::assertEquals('mg', WeightType::MICROGRAM);
        self::assertEquals('mug', WeightType::MILLIGRAM);
        self::assertEquals('g', WeightType::GRAM);
        self::assertEquals('kg', WeightType::KILOGRAM);
        self::assertEquals('t', WeightType::METRIC_TONS);
        self::assertEquals('lb', WeightType::POUNDS);
        self::assertEquals('oz', WeightType::OUNCES);
        self::assertEquals('st', WeightType::STONES);
        self::assertEquals('gr', WeightType::GRAIN);
        self::assertEquals('ct', WeightType::CARAT);
        self::assertEquals('uk t', WeightType::LONG_TONS);
        self::assertEquals('us ton', WeightType::SHORT_TONS);
        self::assertEquals('t lb', WeightType::TROY_POUNDS);
        self::assertEquals('t oz', WeightType::TROY_OUNCES);
    }
}
