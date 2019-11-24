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

use phpOMS\Utils\Converter\FileSizeType;

/**
 * @internal
 */
class FileSizeTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(10, FileSizeType::getConstants());
    }

    /**
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
}
