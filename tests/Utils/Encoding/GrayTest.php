<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Utils\Encoding;

use phpOMS\Utils\Encoding\Gray;

/**
 * @internal
 */
class GrayTest extends \PHPUnit\Framework\TestCase
{
    public function testEncoding() : void
    {
        self::assertEquals(55, Gray::encode(37));
        self::assertEquals(37, Gray::decode(55));
    }

    public function testVolume() : void
    {
        for ($i = 0; $i < 100; ++$i) {
            $raw = \mt_rand(0, 2040140512);

            self::assertEquals($raw, Gray::decode(Gray::encode($raw)));
        }
    }
}
