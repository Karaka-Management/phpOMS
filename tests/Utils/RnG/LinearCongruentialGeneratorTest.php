<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\LinearCongruentialGenerator;

/**
 * @testdox phpOMS\tests\Utils\RnG\LinearCongruentialGeneratorTest: Random number generator
 *
 * @internal
 */
final class LinearCongruentialGeneratorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The bsd random number generator starts with the correct sequence
     * @covers phpOMS\Utils\RnG\LinearCongruentialGenerator
     * @group framework
     */
    public function testBsdRng() : void
    {
        self::assertEquals(12345, LinearCongruentialGenerator::bsd());
    }

    /**
     * @testdox The same bsd seed generates the same random number
     * @covers phpOMS\Utils\RnG\LinearCongruentialGenerator
     * @group framework
     */
    public function testBsdRngEqual() : void
    {
        self::assertEquals(LinearCongruentialGenerator::bsd(1), LinearCongruentialGenerator::bsd(1));
    }

    /**
     * @testdox Different bsd seeds generate different random numbers
     * @covers phpOMS\Utils\RnG\LinearCongruentialGenerator
     * @group framework
     */
    public function testBsdRngNotEqual() : void
    {
        self::assertNotEquals(LinearCongruentialGenerator::bsd(0), LinearCongruentialGenerator::bsd(1));
    }

    /**
     * @testdox The msvcrt random number generator starts with the correct sequence
     * @covers phpOMS\Utils\RnG\LinearCongruentialGenerator
     * @group framework
     */
    public function testMsRng() : void
    {
        self::assertEquals(38, LinearCongruentialGenerator::msvcrt());
        self::assertEquals(7719, LinearCongruentialGenerator::msvcrt());
    }

    /**
     * @testdox The same msvcrt seed generates the same random number
     * @covers phpOMS\Utils\RnG\LinearCongruentialGenerator
     * @group framework
     */
    public function testMsRngEqual() : void
    {
        self::assertEquals(LinearCongruentialGenerator::msvcrt(1), LinearCongruentialGenerator::msvcrt(1));
    }

    /**
     * @testdox Different msvcrt seeds generate different random numbers
     * @covers phpOMS\Utils\RnG\LinearCongruentialGenerator
     * @group framework
     */
    public function testMsRngNotEqual() : void
    {
        self::assertNotEquals(LinearCongruentialGenerator::msvcrt(0), LinearCongruentialGenerator::msvcrt(1));
    }
}
