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

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\DateTime;

/**
 * @testdox phpOMS\tests\Utils\RnG\DateTimeTest: Date time randomizer
 *
 * @internal
 */
class DateTimeTest extends \PHPUnit\Framework\TestCase
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

            $min = \mt_rand(0, \PHP_INT_MAX - 2);
            $max = \mt_rand($min + 1, \PHP_INT_MAX);

            $dateMin->setTimestamp($min);
            $dateMax->setTimestamp($max);

            $rng = DateTime::generateDateTime($dateMin, $dateMax);

            if (!($rng->getTimestamp() >= $min && $rng->getTimestamp() <= $max)) {
                self::assertTrue(false);
            }
        }

        self::assertTrue(true);
    }
}
