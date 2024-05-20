<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\Converter;

use phpOMS\Utils\Converter\VolumeType;

/**
 * @internal
 */
final class VolumeTypeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnumCount() : void
    {
        self::assertCount(38, VolumeType::getConstants());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testUnique() : void
    {
        self::assertEquals(VolumeType::getConstants(), \array_unique(VolumeType::getConstants()));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnums() : void
    {
        self::assertEquals('UK gal', VolumeType::UK_GALLON);
        self::assertEquals('US gal lqd', VolumeType::US_GALLON_LIQUID);
        self::assertEquals('US gal dry', VolumeType::US_GALLON_DRY);
        self::assertEquals('pt', VolumeType::UK_PINT);
        self::assertEquals('US pt lqd', VolumeType::US_PINT_LIQUID);
        self::assertEquals('US pt dry', VolumeType::US_PINT_DRY);
        self::assertEquals('US qt lqd', VolumeType::US_QUARTS_LIQUID);
        self::assertEquals('US qt dry', VolumeType::US_QUARTS_DRY);
        self::assertEquals('UK qt dry', VolumeType::UK_QUARTS);
        self::assertEquals('US gi', VolumeType::US_GILL);
        self::assertEquals('UK gi', VolumeType::UK_GILL);
        self::assertEquals('l', VolumeType::LITER);
        self::assertEquals('mul', VolumeType::MICROLITER);
        self::assertEquals('ml', VolumeType::MILLILITER);
        self::assertEquals('cl', VolumeType::CENTILITER);
        self::assertEquals('kl', VolumeType::KILOLITER);
        self::assertEquals('UK bbl', VolumeType::UK_BARREL);
        self::assertEquals('US bbl dry', VolumeType::US_BARREL_DRY);
        self::assertEquals('US bbl lqd', VolumeType::US_BARREL_LIQUID);
        self::assertEquals('US bbl oil', VolumeType::US_BARREL_OIL);
        self::assertEquals('US bbl fed', VolumeType::US_BARREL_FEDERAL);
        self::assertEquals('us fl oz', VolumeType::US_OUNCES);
        self::assertEquals('uk fl oz', VolumeType::UK_OUNCES);
        self::assertEquals('US tsp', VolumeType::US_TEASPOON);
        self::assertEquals('UK tsp', VolumeType::UK_TEASPOON);
        self::assertEquals('Metric tsp', VolumeType::METRIC_TEASPOON);
        self::assertEquals('US tblsp', VolumeType::US_TABLESPOON);
        self::assertEquals('UK tblsp', VolumeType::UK_TABLESPOON);
        self::assertEquals('Metric tblsp', VolumeType::METRIC_TABLESPOON);
        self::assertEquals('US cup', VolumeType::US_CUP);
        self::assertEquals('Can cup', VolumeType::CAN_CUP);
        self::assertEquals('Metric cup', VolumeType::METRIC_CUP);
        self::assertEquals('cm', VolumeType::CUBIC_CENTIMETER);
        self::assertEquals('mm', VolumeType::CUBIC_MILLIMETER);
        self::assertEquals('m', VolumeType::CUBIC_METER);
        self::assertEquals('in', VolumeType::CUBIC_INCH);
        self::assertEquals('ft', VolumeType::CUBIC_FEET);
        self::assertEquals('yd', VolumeType::CUBIC_YARD);
    }
}
