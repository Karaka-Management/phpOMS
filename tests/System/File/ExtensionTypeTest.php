<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
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
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(14, ExtensionType::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(ExtensionType::getConstants(), \array_unique(ExtensionType::getConstants()));
    }

    /**
     * @group framework
     * @coversNothing
     */
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
