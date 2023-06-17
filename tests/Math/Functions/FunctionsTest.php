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

namespace phpOMS\tests\Math\Functions;

use phpOMS\Math\Functions\Functions;

/**
 * @testdox phpOMS\tests\Math\Functions\FunctionsTest: Various math functions
 *
 * @internal
 */
final class FunctionsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The factorial of a number can be calculated
     * @covers phpOMS\Math\Functions\Functions
     * @group framework
     */
    public function testFactorial() : void
    {
        self::assertEquals(120, Functions::fact(5));
        self::assertEquals(39916800, Functions::fact(11));
    }

    /**
     * @testdox The binomial coefficient can be calculated
     * @covers phpOMS\Math\Functions\Functions
     * @group framework
     */
    public function testBinomialCoefficient() : void
    {
        self::assertEquals(21, Functions::binomialCoefficient(7, 2));
        self::assertEquals(6, Functions::binomialCoefficient(4, 2));
        self::assertEquals(13983816, Functions::binomialCoefficient(49, 6));
    }

    /**
     * @testdox The ackerman function can be calculated
     * @covers phpOMS\Math\Functions\Functions
     * @group framework
     */
    public function testAckermann() : void
    {
        self::assertEquals(5, Functions::ackermann(2, 1));
        self::assertEquals(125, Functions::ackermann(3, 4));
        self::assertEquals(5, Functions::ackermann(0, 4));
        self::assertEquals(13, Functions::ackermann(4, 0));
    }

    /**
     * @testdox The multiplicative inverse module can be calculated
     * @covers phpOMS\Math\Functions\Functions
     * @group framework
     */
    public function testMultiplicativeInverseModulo() : void
    {
        self::assertEquals(4, Functions::invMod(3, -11));
        self::assertEquals(12, Functions::invMod(10, 17));
        self::assertEquals(5, Functions::invMod(-10, 17));
    }

    /**
     * @testdox A number can be checked if it is odd
     * @covers phpOMS\Math\Functions\Functions
     * @group framework
     */
    public function testOdd() : void
    {
        self::assertTrue(Functions::isOdd(3));
        self::assertTrue(Functions::isOdd(-3));
        self::assertFalse(Functions::isOdd(4));
        self::assertFalse(Functions::isOdd(-4));
    }

    /**
     * @testdox A number can be checked if it is even
     * @covers phpOMS\Math\Functions\Functions
     * @group framework
     */
    public function testEven() : void
    {
        self::assertTrue(Functions::isEven(4));
        self::assertTrue(Functions::isEven(-4));
        self::assertFalse(Functions::isEven(3));
        self::assertFalse(Functions::isEven(-3));
    }

    /**
     * @testdox The relative number can be calculated on a circular number system (e.g. month in a diverging business year)
     * @covers phpOMS\Math\Functions\Functions
     * @group framework
     */
    public function testCircularPosition() : void
    {
        self::assertEquals(0, Functions::getRelativeDegree(7, 12, 7));
        self::assertEquals(5, Functions::getRelativeDegree(12, 12, 7));
        self::assertEquals(11, Functions::getRelativeDegree(6, 12, 7));
    }

    /**
     * @testdox The error function can be correctly approximated
     * @covers phpOMS\Math\Functions\Functions
     * @group framework
     */
    public function testErf() : void
    {
        self::assertEqualsWithDelta(-0.8427, Functions::getErf(-1), 0.001);
        self::assertEqualsWithDelta(0.0, Functions::getErf(0), 0.001);
        self::assertEqualsWithDelta(0.8427, Functions::getErf(1), 0.001);
        self::assertEqualsWithDelta(0.9988, Functions::getErf(2.3), 0.001);
    }

    /**
     * @testdox The complementary error function can be correctly approximated
     * @covers phpOMS\Math\Functions\Functions
     * @group framework
     */
    public function testErfc() : void
    {
        self::assertEqualsWithDelta(1.8427, Functions::getErfc(-1), 0.001);
        self::assertEqualsWithDelta(1, Functions::getErfc(0), 0.001);
        self::assertEqualsWithDelta(0.15729920705, Functions::getErfc(1), 0.001);
        self::assertEqualsWithDelta(2.0, Functions::getErfc(-5), 0.001);
    }

    /**
     * @testdox The generalized hypergeometric function can be correctly calculated
     * @covers phpOMS\Math\Functions\Functions
     * @group framework
     */
    public function testGeneralizedHypergeometricFunction() : void
    {
        self::assertEqualsWithDelta(2.7289353, Functions::generalizedHypergeometricFunction([2, 3], [4], 0.5), 0.001);
    }
}
