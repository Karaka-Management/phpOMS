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

namespace phpOMS\tests\Utils\Parser\Calendar;

include_once __DIR__ . '/../../../Autoloader.php';

use phpOMS\System\File\Local\Directory;
use phpOMS\Utils\Parser\Calendar\ICalParser;

/**
 * @internal
 */
final class ICalParserTest extends \PHPUnit\Framework\TestCase
{
    public function testParsing() : void
    {
        $files = Directory::list(__DIR__ . '/data');

        foreach ($files as $file) {
            $data = \explode('.', $file);

            if ($data[1] === 'ical') {
                self::assertEquals(
                    \json_decode(\file_get_contents(__DIR__ . '/data/' . $data[0] . '.json'), true),
                    ICalParser::parse(\file_get_contents(__DIR__ . '/data/' . $data[0] . '.ical'))
                );
            }
        }
    }
}
