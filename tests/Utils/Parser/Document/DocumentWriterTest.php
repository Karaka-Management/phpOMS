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

namespace phpOMS\tests\Utils\Parser\Document;

include_once __DIR__ . '/../../../Autoloader.php';

use PhpOffice\PhpWord\IOFactory;
use phpOMS\Utils\Parser\Document\DocumentWriter;

/**
 * @internal
 */
final class DocumentWriterTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (\is_file(__DIR__ . '/data/WordMpdf.pdf')) {
            \unlink(__DIR__ . '/data/WordMpdf.pdf');
        }
    }

    protected function tearDown() : void
    {
        if (\is_file(__DIR__ . '/data/WordMpdf.pdf')) {
            \unlink(__DIR__ . '/data/WordMpdf.pdf');
        }
    }

    public function testParsing() : void
    {
        $doc    = IOFactory::load(__DIR__ . '/data/Word.docx');
        $writer = new DocumentWriter($doc);

        $pdf = $writer->toPdfString(__DIR__ . '/data/WordMpdf.pdf');
        self::assertFalse(\is_file(__DIR__ . '/data/WordMpdf.pdf'));

        \file_put_contents(__DIR__ . '/data/WordMpdf.pdf', $pdf);
        self::assertTrue(\is_file(__DIR__ . '/data/WordMpdf.pdf'));
        self::assertGreaterThan(100, \strlen(\file_get_contents(__DIR__ . '/data/WordMpdf.pdf')));
    }
}
