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

namespace phpOMS\tests\Utils\Parser\Markdown;

include_once __DIR__ . '/../../../Autoloader.php';

use phpOMS\System\File\Local\Directory;
use phpOMS\Utils\Parser\Markdown\Markdown;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\Parser\Markdown\Markdown::class)]
final class MarkdownTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
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
        $parser           = new Markdown();
        $parser->safeMode = true;

        self::assertEquals(
            \file_get_contents(__DIR__ . '/manualdata/xss_bad_url.html'),
            $parser->text(\file_get_contents(__DIR__ . '/manualdata/xss_bad_url.md'))
        );
    }

    public function testTablespan() : void
    {
        $parser = new Markdown([
            'tables' => [
                'tablespan' => true,
            ],
        ]);

        self::assertEquals(
            \file_get_contents(__DIR__ . '/manualdata/tablespan.html'),
            $parser->text(\file_get_contents(__DIR__ . '/manualdata/tablespan.md'))
        );
    }

    public function testMap() : void
    {
        $parser = new Markdown([
            'map' => true,
        ]);

        self::assertLessThan(9,
            \levenshtein(
                \file_get_contents(__DIR__ . '/manualdata/map.html'),
                $parser->text(\file_get_contents(__DIR__ . '/manualdata/map.md'))
            )
        );
    }

    public function testContact() : void
    {
        $parser = new Markdown([
            'contact' => true,
        ]);

        self::assertEquals(
            \file_get_contents(__DIR__ . '/manualdata/contact.html'),
            $parser->text(\file_get_contents(__DIR__ . '/manualdata/contact.md'))
        );
    }

    public function testTypographer() : void
    {
        $parser = new Markdown([
            'typographer' => true,
        ]);

        self::assertEquals(
            \file_get_contents(__DIR__ . '/manualdata/typographer.html'),
            $parser->text(\file_get_contents(__DIR__ . '/manualdata/typographer.md'))
        );
    }

    public function testAddress() : void
    {
        $parser = new Markdown([
            'address' => true,
        ]);

        self::assertEquals(
            \file_get_contents(__DIR__ . '/manualdata/address.html'),
            $parser->text(\file_get_contents(__DIR__ . '/manualdata/address.md'))
        );
    }

    public function testProgress() : void
    {
        $parser = new Markdown([
            'progress' => true,
        ]);

        self::assertEquals(
            \file_get_contents(__DIR__ . '/manualdata/progress.html'),
            $parser->text(\file_get_contents(__DIR__ . '/manualdata/progress.md'))
        );
    }

    public function testEmbed() : void
    {
        $parser = new Markdown([
            'embedding' => true,
        ]);

        self::assertEquals(
            \file_get_contents(__DIR__ . '/manualdata/embed.html'),
            $parser->text(\file_get_contents(__DIR__ . '/manualdata/embed.md'))
        );
    }

    public function testMath() : void
    {
        $parser = new Markdown([
            'math' => true,
        ]);

        self::assertEquals(
            \file_get_contents(__DIR__ . '/manualdata/katex.html'),
            $parser->text(\file_get_contents(__DIR__ . '/manualdata/katex.md'))
        );
    }

    public function testTOC() : void
    {
        $parser = new Markdown([
            'toc' => true,
        ]);
        $parser->text(\file_get_contents(__DIR__ . '/manualdata/toc.md'));

        self::assertEquals(
            \file_get_contents(__DIR__ . '/manualdata/toc.html'),
            $parser->contentsList()
        );
    }

    public function testSpoiler() : void
    {
        $parser = new Markdown([
            'spoiler' => true,
        ]);

        self::assertEquals(
            \file_get_contents(__DIR__ . '/manualdata/spoiler.html'),
            $parser->text(\file_get_contents(__DIR__ . '/manualdata/spoiler.md'))
        );

        self::assertEquals(
            \file_get_contents(__DIR__ . '/manualdata/spoiler_block.html'),
            $parser->text(\file_get_contents(__DIR__ . '/manualdata/spoiler_block.md'))
        );
    }
}
