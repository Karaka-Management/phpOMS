<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\StringUtils;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\RnG\StringUtils::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\RnG\StringUtilsTest: Random string generator')]
final class StringUtilsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @slowThreshold 1500
     */
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Random strings can be generated')]
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
