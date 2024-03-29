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

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\StringUtils;

/**
 * @testdox phpOMS\tests\Utils\RnG\StringUtilsTest: Random string generator
 *
 * @internal
 */
final class StringUtilsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Random strings can be generated
     * @covers phpOMS\Utils\RnG\StringUtils
     * @slowThreshold 1500
     * @group framework
     */
    public function testStrings() : void
    {
        $haystack    = [];
        $outOfBounds = false;
        $randomness  = 0;

        for ($i = 0; $i < 1000; ++$i) {
            $random = StringUtils::generateString(5, 12, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_?><|;"');

            if (\strlen($random) > 12 || \strlen($random) < 5) {
                $outOfBounds = true;
            }

            if (\in_array($random, $haystack)) {
                ++$randomness;
            }

            $haystack[] = $random;
        }

        self::assertFalse($outOfBounds);
        self::assertLessThan(5, $randomness);
    }
}
