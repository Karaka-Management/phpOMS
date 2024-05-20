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

use phpOMS\Utils\Converter\FileSizeType;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\Converter\FileSizeType::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\Converter\FileSizeTypeTest: File size types')]
final class FileSizeTypeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnumCount() : void
    {
        self::assertCount(10, FileSizeType::getConstants());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testUnique() : void
    {
        self::assertEquals(FileSizeType::getConstants(), \array_unique(FileSizeType::getConstants()));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('File sizes can get automatically formatted according to their size')]
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
