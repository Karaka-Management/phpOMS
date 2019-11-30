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

namespace phpOMS\tests\Utils\Encoding;

use phpOMS\Utils\Encoding\Gray;

/**
 * @testdox phpOMS\tests\Utils\Encoding\GrayTest: Gray text encoding/decoding
 *
 * @internal
 */
class GrayTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Text can be encoded and decoded with the gray encoding
     * @covers phpOMS\Utils\Encoding\Gray
     */
    public function testEncoding() : void
    {
        self::assertEquals(55, Gray::encode(37));
        self::assertEquals(37, Gray::decode(55));
    }
}
