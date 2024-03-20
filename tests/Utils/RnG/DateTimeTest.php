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

use phpOMS\Utils\RnG\DateTime;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\RnG\DateTime::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\RnG\DateTimeTest: Date time randomizer')]
final class DateTimeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A random date time can be generated')]
    public function testRnG() : void
    {
        for ($i = 0; $i < 100; ++$i) {
            $dateMin = new \DateTime();
            $dateMax = new \DateTime();

            $min = \mt_rand(0, (int) (2147483647 / 2));
            $max = \mt_rand($min + 10, 2147483647);

            $dateMin->setTimestamp($min);
            $dateMax->setTimestamp($max);

            $rng = DateTime::generateDateTime($dateMin, $dateMax);

            if ($rng->getTimestamp() < $min || $rng->getTimestamp() > $max) {
                self::assertTrue(false);
            }
        }

        self::assertTrue(true);
    }
}
