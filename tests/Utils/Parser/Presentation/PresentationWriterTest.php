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

namespace phpOMS\tests\Utils\Parser\Presentation;

include_once __DIR__ . '/../../../Autoloader.php';

use phpOMS\Utils\Parser\Presentation\PresentationWriter;
use PhpOffice\PhpPresentation\IOFactory;

/**
 * @internal
 */
final class PresentationWriterTest extends \PHPUnit\Framework\TestCase
{
    public function testParsing() : void
    {
        $presentation = IOFactory::load(__DIR__ . '/data/Powerpoint.pptx');

        $writer = new PresentationWriter($presentation);

        self::assertTrue(
            abs(\strlen(\file_get_contents(__DIR__ . '/data/Powerpoint.html'))
            - \strlen($writer->renderHtml())) < 100
        );
    }
}
