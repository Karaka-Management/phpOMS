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

namespace phpOMS\tests\Utils\Converter;

use phpOMS\Utils\Converter\WeightType;

/**
 * @internal
 */
final class WeightTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(14, WeightType::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(WeightType::getConstants(), \array_unique(WeightType::getConstants()));
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
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
