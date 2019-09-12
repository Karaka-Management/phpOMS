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

namespace phpOMS\tests\Math\Number;

use phpOMS\Math\Number\Integer;

/**
 * @internal
 */
class IntegerTest extends \PHPUnit\Framework\TestCase
{
    public function testIsInteger() : void
    {
        self::assertTrue(Integer::isInteger(4));
        self::assertFalse(Integer::isInteger(1.0));
        self::assertFalse(Integer::isInteger('3'));
    }

    public function testFactorization() : void
    {
        self::assertArraySubset([2, 2, 5, 5], Integer::trialFactorization(100));
        self::assertArraySubset([2], Integer::trialFactorization(2));
        self::assertEquals([], Integer::trialFactorization(1));
    }

    public function testOther() : void
    {
        self::assertEquals(101, Integer::pollardsRho(10403, 2, 1, 2, 2));

        self::assertEquals([59, 101], Integer::fermatFactor(5959));
    }

    public function testInvalidFermatParameter() : void
    {
        self::expectException(\Exception::class);

        Integer::fermatFactor(8);
    }

    public function testGCD() : void
    {
        self::assertEquals(4, Integer::greatestCommonDivisor(4, 4));
        self::assertEquals(6, Integer::greatestCommonDivisor(54, 24));
        self::assertEquals(6, Integer::greatestCommonDivisor(24, 54));
        self::assertEquals(1, Integer::greatestCommonDivisor(7, 13));
    }
}
