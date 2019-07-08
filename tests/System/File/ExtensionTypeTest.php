<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\System\File;

use phpOMS\System\File\ExtensionType;

/**
 * @internal
 */
class ExtensionTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(12, ExtensionType::getConstants());
        self::assertEquals(ExtensionType::getConstants(), \array_unique(ExtensionType::getConstants()));

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
    }
}
