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
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\IO\Csv;

use phpOMS\Utils\IO\Csv\CsvSettings;

/**
 * @testdox phpOMS\tests\Utils\IO\Csv\CsvSettingsTest: Csv file settings
 *
 * @internal
 */
final class CsvSettingsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The delimiter in a csv file can be guessed
     * @covers phpOMS\Utils\IO\Csv\CsvSettings
     * @group framework
     */
    public function testFileDelimiter() : void
    {
        self::assertEquals(':', CsvSettings::getFileDelimiter(\fopen(__DIR__ . '/colon.csv', 'r')));
        self::assertEquals(',', CsvSettings::getFileDelimiter(\fopen(__DIR__ . '/comma.csv', 'r')));
        self::assertEquals('|', CsvSettings::getFileDelimiter(\fopen(__DIR__ . '/pipe.csv', 'r')));
        self::assertEquals(';', CsvSettings::getFileDelimiter(\fopen(__DIR__ . '/semicolon.csv', 'r')));
    }

    /**
     * @testdox The delimiter in a csv string can be guessed
     * @covers phpOMS\Utils\IO\Csv\CsvSettings
     * @group framework
     */
    public function testStringDelimiter() : void
    {
        self::assertEquals(':', CsvSettings::getStringDelimiter(\file_get_contents(__DIR__ . '/colon.csv')));
        self::assertEquals(',', CsvSettings::getStringDelimiter(\file_get_contents(__DIR__ . '/comma.csv')));
        self::assertEquals('|', CsvSettings::getStringDelimiter(\file_get_contents(__DIR__ . '/pipe.csv')));
        self::assertEquals(';', CsvSettings::getStringDelimiter(\file_get_contents(__DIR__ . '/semicolon.csv')));
    }
}
