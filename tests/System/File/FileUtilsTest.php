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
        self::assertEquals(\realpath(__DIR__ . '/..'), FileUtils::absolute(__DIR__ . '/..'));
        self::assertEquals('/test/ative', FileUtils::absolute('/test/path/for/../rel/../../ative'));
    }

    /**
     * @testdox Permissions can be turned into octal values
     * @covers phpOMS\System\File\FileUtils
     * @group framework
     */
    public function testPermissionToOctal() : void
    {
        self::assertEquals(0742, FileUtils::permissionToOctal('rwxr---w-'));
    }

    /**
     * @testdox The encoding of a file can be changed
     * @covers phpOMS\System\File\FileUtils
     * @group framework
     */
    public function testChangeFileEncoding() : void
    {
        if (\is_file(__DIR__ . '/UTF-8.txt')) {
            \unlink(__DIR__ . '/UTF-8.txt');
        }

        FileUtils::changeFileEncoding(__DIR__ . '/Windows-1252.txt', __DIR__ . '/UTF-8.txt', 'UTF-8', 'Windows-1252');

        self::assertFileExists(__DIR__ . '/UTF-8.txt');
        self::assertNotEquals("This is a test file with some¶\ncontent Ø Æ.", \file_get_contents(__DIR__ . '/Windows-1252.txt'));
        self::assertEquals("This is a test file with some¶\ncontent Ø Æ.", \file_get_contents(__DIR__ . '/UTF-8.txt'));

        if (\is_file(__DIR__ . '/UTF-8.txt')) {
            \unlink(__DIR__ . '/UTF-8.txt');
        }
    }

    /**
     * @testdox The file information can be resolved from a path
     * @covers phpOMS\System\File\FileUtils
     * @group framework
     */
    public function testPathInfo() : void
    {
        self::assertEquals(__DIR__, FileUtils::mb_pathinfo(__DIR__ . '/FileUtilsTest.php', \PATHINFO_DIRNAME));
        self::assertEquals(\basename(__DIR__ . '/FileUtilsTest.php'), FileUtils::mb_pathinfo(__DIR__ . '/FileUtilsTest.php', \PATHINFO_BASENAME));
        self::assertEquals('php', FileUtils::mb_pathinfo(__DIR__ . '/FileUtilsTest.php', \PATHINFO_EXTENSION));
        self::assertEquals('FileUtilsTest', FileUtils::mb_pathinfo(__DIR__ . '/FileUtilsTest.php', \PATHINFO_FILENAME));

        self::assertEquals(
            [
                'dirname'   => __DIR__,
                'basename'  => \basename(__DIR__ . '/FileUtilsTest.php'),
                'extension' => 'php',
                'filename'  => 'FileUtilsTest',
            ],
            FileUtils::mb_pathinfo(__DIR__ . '/FileUtilsTest.php')
        );
    }
}
