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

namespace phpOMS\tests\Math\Functions;

use phpOMS\Math\Functions\Functions;

class FunctionsTest extends \PHPUnit\Framework\TestCase
{
    public function testFactorial()
    {
        self::assertEquals(120, Functions::fact(5));
        self::assertEquals(39916800, Functions::fact(11));

        self::assertEquals(21, Functions::binomialCoefficient(7, 2));
        self::assertEquals(6, Functions::binomialCoefficient(4, 2));
        self::assertEquals(13983816, Functions::binomialCoefficient(49, 6));
    }

    public function testAckermann()
    {
        self::assertEquals(5, Functions::ackermann(2, 1));
        self::assertEquals(125, Functions::ackermann(3, 4));
        self::assertEquals(5, Functions::ackermann(0, 4));
        self::assertEquals(13, Functions::ackermann(4, 0));
    }

    public function testMultiplicativeInverseModulo()
    {
        self::assertEquals(4, Functions::invMod(3, -11));
        self::assertEquals(12, Functions::invMod(10, 17));
        self::assertEquals(5, Functions::invMod(-10, 17));
    }

    public function testAbs()
    {
        self::assertEquals([1, 3, 4], Functions::abs([-1, 3, -4]));
    }

    public function testProperties()
    {
        self::assertTrue(Functions::isOdd(3));
        self::assertTrue(Functions::isOdd(-3));
        self::assertFalse(Functions::isOdd(4));
        self::assertFalse(Functions::isOdd(-4));

        self::assertTrue(Functions::isEven(4));
        self::assertTrue(Functions::isEven(-4));
        self::assertFalse(Functions::isEven(3));
        self::assertFalse(Functions::isEven(-3));
    }

    public function testCircularPosition()
    {
        self::assertEquals(0, Functions::getRelativeDegree(7, 12, 7));
        self::assertEquals(5, Functions::getRelativeDegree(12, 12, 7));
        self::assertEquals(11, Functions::getRelativeDegree(6, 12, 7));
    }
    
    public function testPower()
    {
        self::assertEquals([4, 9, 16], Functions::powerInt([2, 3, 4], 2));
        self::assertEquals([8, 27, 64], Functions::powerInt([2, 3, 4], 3));
        
        self::assertEquals([2.0, 3.0, 4.0], Functions::powerFloat([4, 9, 16], 1/2), '', 0.0);
        self::assertEquals([2.0, 3.0, 4.0], Functions::powerFloat([8, 27, 64], 1/3), '', 0.0);
    }
}
