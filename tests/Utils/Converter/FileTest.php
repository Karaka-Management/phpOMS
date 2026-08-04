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

use phpOMS\Utils\Converter\File;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\Converter\File::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\Converter\FileTest: File size converter')]
final class FileTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A byte number can be converted to a string representation')]
    public function testByteSizeToString() : void
    {
        self::assertEquals('400b', File::byteSizeToString(400));
        self::assertEquals('5kb', File::byteSizeToString(5000));
        self::assertEquals('7mb', File::byteSizeToString(7000000));
        self::assertEquals('1.5gb', File::byteSizeToString(1500000000));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A kilobyte number can be converted to a string representation')]
    public function testKilobyteSizeToString() : void
    {
        self::assertEquals('500kb', File::kilobyteSizeToString(500));
        self::assertEquals('5mb', File::kilobyteSizeToString(5000));
        self::assertEquals('5.4gb', File::kilobyteSizeToString(5430000));
    }
}
