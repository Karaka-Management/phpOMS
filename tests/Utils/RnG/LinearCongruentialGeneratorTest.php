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
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\LinearCongruentialGenerator;

class LinearCongruentialGeneratorTest extends \PHPUnit\Framework\TestCase
{
    public function testBsdRng()
    {
        self::assertEquals(12345, LinearCongruentialGenerator::bsd());

        if (PHP_INT_SIZE > 4) {
            self::assertEquals(1406932606, LinearCongruentialGenerator::bsd());
            self::assertEquals(654583775, LinearCongruentialGenerator::bsd());
            self::assertEquals(1449466924, LinearCongruentialGenerator::bsd());
        }

        self::assertEquals(LinearCongruentialGenerator::bsd(1), LinearCongruentialGenerator::bsd(1));
        self::assertNotEquals(LinearCongruentialGenerator::bsd(0), LinearCongruentialGenerator::bsd(1));
    }

    public function testMsRng()
    {
        self::assertEquals(38, LinearCongruentialGenerator::msvcrt());
        self::assertEquals(7719, LinearCongruentialGenerator::msvcrt());

        if (PHP_INT_SIZE > 4) {
            self::assertEquals(21238, LinearCongruentialGenerator::msvcrt());
            self::assertEquals(2437, LinearCongruentialGenerator::msvcrt());
        }

        self::assertEquals(LinearCongruentialGenerator::msvcrt(1), LinearCongruentialGenerator::msvcrt(1));
        self::assertNotEquals(LinearCongruentialGenerator::msvcrt(0), LinearCongruentialGenerator::msvcrt(1));
    }
}
