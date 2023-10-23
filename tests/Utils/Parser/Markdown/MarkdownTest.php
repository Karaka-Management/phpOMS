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

namespace phpOMS\tests\Utils\Parser\Markdown;

include_once __DIR__ . '/../../../Autoloader.php';

use phpOMS\System\File\Local\Directory;
use phpOMS\Utils\Parser\Markdown\Markdown;

/**
 * @internal
 */
final class MarkdownTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Utils\Parser\Markdown\Markdown
     * @group framework
     */
    public function testParsing() : void
    {
        $files = Directory::list(__DIR__ . '/data');

        foreach ($files as $file) {
            $data = \explode('.', $file);

            if ($data[1] === 'md'
                && (\file_get_contents(__DIR__ . '/data/' . $data[0] . '.html') !== ($parsed = Markdown::parse(\file_get_contents(__DIR__ . '/data/' . $data[0] . '.md'))))
            ) {
                self::assertTrue(false, $file . "\n\n" . $parsed);
            }
        }

        self::assertTrue(true);
    }

    public function testSafeMode() : void
    {
        $parser = new Markdown();
        $parser->setSafeMode(true);

        self::assertEquals(
            \file_get_contents(__DIR__ . '/manualdata/xss_bad_url.html'),
            $parser->text(\file_get_contents(__DIR__ . '/manualdata/xss_bad_url.md'))
        );
    }

    public function testTablespan() : void
    {
        $parser = new Markdown([
            'tables' => [
                'tablespan' => true
            ]
        ]);

        self::assertEquals(
            \file_get_contents(__DIR__ . '/manualdata/tablespan.html'),
            $parser->text(\file_get_contents(__DIR__ . '/manualdata/tablespan.md'))
        );
    }

    public function testMath() : void
    {
        $parser = new Markdown([
            'math' => true
        ]);

        self::assertEquals(
            \file_get_contents(__DIR__ . '/manualdata/katex.html'),
            $parser->text(\file_get_contents(__DIR__ . '/manualdata/katex.md'))
        );
    }

    public function testTOC() : void
    {
        $parser = new Markdown([
            'toc' => true
        ]);
        $parser->text(\file_get_contents(__DIR__ . '/manualdata/toc.md'));

        self::assertEquals(
            \file_get_contents(__DIR__ . '/manualdata/toc.html'),
            $parser->contentsList()
        );
    }
}
