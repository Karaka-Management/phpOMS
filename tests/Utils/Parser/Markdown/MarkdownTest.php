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

namespace phpOMS\tests\Utils\Parser\Markdown;

use phpOMS\System\File\Local\Directory;
use phpOMS\Utils\Parser\Markdown\Markdown;

/**
 * @internal
 */
class MarkdownTest extends \PHPUnit\Framework\TestCase
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
}
