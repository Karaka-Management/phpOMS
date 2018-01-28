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

namespace phpOMS\tests\Utils\Encoding;

use phpOMS\Utils\Encoding\Caesar;
use phpOMS\Utils\RnG\StringUtils;

class CaesarTest extends \PHPUnit\Framework\TestCase
{
    public function testVolume()
    {
        for($i = 0; $i < 100; $i++) {
            $raw = StringUtils::generateString(1, 100);
            $key = StringUtils::generateString(1, 100);

            self::assertNotEquals($raw, Caesar::encode($raw, $key));
            self::assertEquals($raw, Caesar::decode(Caesar::encode($raw, $key), $key));
        }
    }
}
