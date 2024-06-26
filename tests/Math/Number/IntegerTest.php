<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Number;

use phpOMS\Math\Number\Integer;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Number\Integer::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Number\IntegerTest: Integer operations')]
final class IntegerTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A value can be checked to be an integer')]
    public function testIsInteger() : void
    {
        self::assertTrue(Integer::isInteger(4));
        self::assertFalse(Integer::isInteger(1.0));
        self::assertFalse(Integer::isInteger('3'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An integer can be factorized')]
    public function testFactorization() : void
    {
        $arr      = [2, 2, 5, 5];
        $isSubset = true;
        $parent   = Integer::trialFactorization(100);
        foreach ($arr as $key => $value) {
            if (!isset($parent[$key]) || $parent[$key] !== $value) {
                $isSubset = false;
                break;
            }
        }
        self::assertTrue($isSubset);

        $arr      = [2];
        $isSubset = true;
        $parent   = Integer::trialFactorization(2);
        foreach ($arr as $key => $value) {
            if (!isset($parent[$key]) || $parent[$key] !== $value) {
                $isSubset = false;
                break;
            }
        }
        self::assertTrue($isSubset);

        self::assertEquals([], Integer::trialFactorization(1));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("The Pollard's Roh algorithm calculates a factor of an integer")]
    public function testPollardsRho() : void
    {
        self::assertEquals(101, Integer::pollardsRho(10403, 2, 1, 2, 2));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The Fermat factorization calculates a factor of an integer')]
    public function testFermatFactor() : void
    {
        self::assertEquals([59, 101], Integer::fermatFactor(5959));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A even number for the fermat factorization throws a InvalidArgumentException')]
    public function testInvalidFermatParameter() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Integer::fermatFactor(8);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The greatest common divisor can be calculated')]
    public function testGCD() : void
    {
        self::assertEquals(4, Integer::greatestCommonDivisor(4, 4));
        self::assertEquals(6, Integer::greatestCommonDivisor(54, 24));
        self::assertEquals(6, Integer::greatestCommonDivisor(24, 54));
        self::assertEquals(1, Integer::greatestCommonDivisor(7, 13));
    }
}
