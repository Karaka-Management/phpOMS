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

namespace phpOMS\tests\System\File;

use phpOMS\System\File\ExtensionType;
use phpOMS\System\File\FileUtils;

/**
 * @testdox phpOMS\tests\System\File\FileUtilsTest: File utilities
 *
 * @internal
 */
class FileUtilsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox File extensions can be categorized
     * @covers phpOMS\System\File\FileUtils
     * @group framework
     */
    public function testExtension() : void
    {
        self::assertEquals(ExtensionType::UNKNOWN, FileUtils::getExtensionType('test'));
        self::assertEquals(ExtensionType::CODE, FileUtils::getExtensionType('php'));
        self::assertEquals(ExtensionType::TEXT, FileUtils::getExtensionType('md'));
        self::assertEquals(ExtensionType::PRESENTATION, FileUtils::getExtensionType('pptx'));
        self::assertEquals(ExtensionType::PDF, FileUtils::getExtensionType('pdf'));
        self::assertEquals(ExtensionType::ARCHIVE, FileUtils::getExtensionType('rar'));
        self::assertEquals(ExtensionType::AUDIO, FileUtils::getExtensionType('mp3'));
        self::assertEquals(ExtensionType::VIDEO, FileUtils::getExtensionType('mp4'));
        self::assertEquals(ExtensionType::SPREADSHEET, FileUtils::getExtensionType('xls'));
        self::assertEquals(ExtensionType::IMAGE, FileUtils::getExtensionType('png'));
        self::assertEquals(ExtensionType::WORD, FileUtils::getExtensionType('doc'));
        self::assertEquals(ExtensionType::WORD, FileUtils::getExtensionType('docx'));
        self::assertEquals(ExtensionType::DIRECTORY, FileUtils::getExtensionType('collection'));
        self::assertEquals(ExtensionType::DIRECTORY, FileUtils::getExtensionType('/'));
    }

    /**
     * @testdox A relative path can be turned into an absolute path
     * @covers phpOMS\System\File\FileUtils
     * @group framework
     */
    public function testAbsolute() : void
    {
        self::assertEquals('/test/ative', FileUtils::absolute('/test/path/for/../rel/../../ative'));
    }

    /**
     * @testdox Permissions can be turned into ocal values
     * @covers phpOMS\System\File\FileUtils
     * @group framework
     */
    public function testPermissionToOctal() : void
    {
        self::assertEquals(0742, FileUtils::permissionToOctal('rwxr---w-'));
    }
}
