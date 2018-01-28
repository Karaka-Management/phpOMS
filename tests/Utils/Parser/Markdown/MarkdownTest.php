<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Utils\Parser\Markdown;

use phpOMS\Utils\Parser\Markdown\Markdown;
use phpOMS\System\File\Local\Directory;

class MarkdownTest extends \PHPUnit\Framework\TestCase
{
    public function testParsing()
    {
        $files = Directory::list(__DIR__ . '/data');

        foreach ($files as $file) {
            $data = explode('.', $file);

            if ($data[1] === 'md') {
                self::assertEquals(
                    file_get_contents(__DIR__ . '/data/' . $data[0] . '.html'),
                    Markdown::parse(file_get_contents(__DIR__ . '/data/' . $data[0] . '.md')),
                    $file
                );
            }
        }
    }
}

