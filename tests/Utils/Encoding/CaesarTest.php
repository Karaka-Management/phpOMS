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

use phpOMS\Utils\Encoding\Caesar;
use phpOMS\Utils\RnG\StringUtils;

/**
 * @testdox phpOMS\tests\Utils\Encoding\CaesarTest: Caesar text encoding/decoding
 * @internal
 */
class CaesarTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Text can be encoded and decoded with the ceasar encoding
     * @covers phpOMS\Utils\Encoding\Caesar
     * @group framework
     */
    public function testEncoding() : void
    {
        $raw = StringUtils::generateString(1, 100);
        $key = StringUtils::generateString(1, 100);

        self::assertNotEquals($raw, Caesar::encode($raw, $key));
        self::assertEquals($raw, Caesar::decode(Caesar::encode($raw, $key), $key));
    }
}
