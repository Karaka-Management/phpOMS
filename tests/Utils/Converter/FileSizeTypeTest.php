<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\Converter;

use phpOMS\Utils\Converter\FileSizeType;

/**
 * @testdox phpOMS\tests\Utils\Converter\FileSizeTypeTest: File size types
 *
 * @internal
 */
class FileSizeTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(10, FileSizeType::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(FileSizeType::getConstants(), array_unique(FileSizeType::getConstants()));
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals('TB', FileSizeType::TERRABYTE);
        self::assertEquals('GB', FileSizeType::GIGABYTE);
        self::assertEquals('MB', FileSizeType::MEGABYTE);
        self::assertEquals('KB', FileSizeType::KILOBYTE);
        self::assertEquals('B', FileSizeType::BYTE);
        self::assertEquals('tbit', FileSizeType::TERRABIT);
        self::assertEquals('gbit', FileSizeType::GIGABIT);
        self::assertEquals('mbit', FileSizeType::MEGABIT);
        self::assertEquals('kbit', FileSizeType::KILOBIT);
        self::assertEquals('bit', FileSizeType::BIT);
    }

    /**
     * @testdox File sizes can get automatically formatted according to their size
     * @covers phpOMS\Utils\Converter\FileSizeType
     * @group framework
     */
    public function testAutoFormat() : void
    {
        self::assertEquals(
            [250.0, 'B'],
            FileSizeType::autoFormat(250)
        );

        self::assertEquals(
            [0.5, 'KB'],
            FileSizeType::autoFormat(500)
        );

        self::assertEquals(
            [1.024, 'MB'],
            FileSizeType::autoFormat(1024 * 1000)
        );

        self::assertEquals(
            [1.024, 'GB'],
            FileSizeType::autoFormat(1024 * 1000 * 1000)
        );
    }
}
