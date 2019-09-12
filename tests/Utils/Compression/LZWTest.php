<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Utils\Compression;

use phpOMS\Utils\Compression\LZW;

/**
 * @internal
 */
class LZWTest extends \PHPUnit\Framework\TestCase
{
    public function testLZW() : void
    {
        $expected    = 'This is a test';
        $compression = new LZW();
        self::assertEquals($expected, $compression->decompress($compression->compress($expected)));
    }
}
