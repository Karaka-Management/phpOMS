<?php
/**
 * Jingga
 *
 * PHP Version 8.1
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
    public function testParsing() : void
    {
        $doc = IOFactory::load(__DIR__ . '/data/Word.docx');
        $writer = new DocumentWriter($doc);

        $pdf = $writer->toPdfString(__DIR__ . '/data/Mpdf.pdf');
        self::assertTrue(\is_file(__DIR__ . '/data/Mpdf.pdf'));
    }
}
