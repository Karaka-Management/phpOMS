<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\DateTime;

/**
 * @testdox phpOMS\tests\Utils\RnG\DateTimeTest: Date time randomizer
 *
 * @internal
 */
final class DateTimeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A random date time can be generated
     * @covers phpOMS\Utils\RnG\DateTime
     * @group framework
     */
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
