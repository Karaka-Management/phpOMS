<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\System\File;

use phpOMS\System\File\ExtensionType;

/**
 * @internal
 */
final class ExtensionTypeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnumCount() : void
    {
        self::assertCount(14, ExtensionType::getConstants());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testUnique() : void
    {
        self::assertEquals(ExtensionType::getConstants(), \array_unique(ExtensionType::getConstants()));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnums() : void
    {
        self::assertEquals(1, ExtensionType::UNKNOWN);
        self::assertEquals(2, ExtensionType::CODE);
        self::assertEquals(4, ExtensionType::AUDIO);
        self::assertEquals(8, ExtensionType::VIDEO);
        self::assertEquals(16, ExtensionType::TEXT);
        self::assertEquals(32, ExtensionType::SPREADSHEET);
        self::assertEquals(64, ExtensionType::PDF);
        self::assertEquals(128, ExtensionType::ARCHIVE);
        self::assertEquals(256, ExtensionType::PRESENTATION);
        self::assertEquals(512, ExtensionType::IMAGE);
        self::assertEquals(1024, ExtensionType::EXECUTABLE);
        self::assertEquals(2048, ExtensionType::DIRECTORY);
        self::assertEquals(4096, ExtensionType::WORD);
        self::assertEquals(8192, ExtensionType::REFERENCE);
    }
}
