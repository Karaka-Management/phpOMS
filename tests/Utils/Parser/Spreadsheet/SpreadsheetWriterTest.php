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

namespace phpOMS\tests\Utils\Parser\Spreadsheet;

include_once __DIR__ . '/../../../Autoloader.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use phpOMS\Utils\Parser\Spreadsheet\SpreadsheetWriter;

/**
 * @internal
 */
final class SpreadsheetWriterTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (\is_file(__DIR__ . '/data/ExcelMpdf.pdf')) {
            \unlink(__DIR__ . '/data/ExcelMpdf.pdf');
        }
    }

    protected function tearDown() : void
    {
        if (\is_file(__DIR__ . '/data/ExcelMpdf.pdf')) {
            \unlink(__DIR__ . '/data/ExcelMpdf.pdf');
        }
    }

    public function testParsing() : void
    {
        $sheet  = IOFactory::load(__DIR__ . '/data/Excel.xlsx');
        $writer = new SpreadsheetWriter($sheet);

        $pdf = $writer->toPdfString(__DIR__ . '/data/ExcelMpdf.pdf');
        self::assertFalse(\is_file(__DIR__ . '/data/ExcelMpdf.pdf'));

        \file_put_contents(__DIR__ . '/data/ExcelMpdf.pdf', $pdf);
        self::assertTrue(\is_file(__DIR__ . '/data/ExcelMpdf.pdf'));
        self::assertGreaterThan(100, \strlen(\file_get_contents(__DIR__ . '/data/ExcelMpdf.pdf')));
    }
}
