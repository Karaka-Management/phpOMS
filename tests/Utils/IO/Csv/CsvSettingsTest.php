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

namespace phpOMS\tests\Utils\IO\Csv;

use phpOMS\Utils\IO\Csv\CsvSettings;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\IO\Csv\CsvSettings::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\IO\Csv\CsvSettingsTest: Csv file settings')]
final class CsvSettingsTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The delimiter in a csv file can be guessed')]
    public function testFileDelimiter() : void
    {
        self::assertEquals(':', CsvSettings::getFileDelimiter(\fopen(__DIR__ . '/colon.csv', 'r')));
        self::assertEquals(',', CsvSettings::getFileDelimiter(\fopen(__DIR__ . '/comma.csv', 'r')));
        self::assertEquals('|', CsvSettings::getFileDelimiter(\fopen(__DIR__ . '/pipe.csv', 'r')));
        self::assertEquals(';', CsvSettings::getFileDelimiter(\fopen(__DIR__ . '/semicolon.csv', 'r')));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The delimiter in a csv string can be guessed')]
    public function testStringDelimiter() : void
    {
        self::assertEquals(':', CsvSettings::getStringDelimiter(\file_get_contents(__DIR__ . '/colon.csv')));
        self::assertEquals(',', CsvSettings::getStringDelimiter(\file_get_contents(__DIR__ . '/comma.csv')));
        self::assertEquals('|', CsvSettings::getStringDelimiter(\file_get_contents(__DIR__ . '/pipe.csv')));
        self::assertEquals(';', CsvSettings::getStringDelimiter(\file_get_contents(__DIR__ . '/semicolon.csv')));
    }
}
