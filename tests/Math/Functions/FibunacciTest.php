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

namespace phpOMS\tests\Math\Functions;

use phpOMS\Math\Functions\Fibunacci;

/**
 * @testdox phpOMS\tests\Math\Functions\FibunacciTest: Fibunacci functions
 *
 * @internal
 */
class FibunacciTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A number can be checked if it is a fibunacci number
     * @covers phpOMS\Math\Functions\Fibunacci
     * @group framework
     */
    public function testFibunacci() : void
    {
        self::assertTrue(Fibunacci::isFibunacci(13));
        self::assertTrue(Fibunacci::isFibunacci(55));
        self::assertTrue(Fibunacci::isFibunacci(89));
        self::assertFalse(Fibunacci::isFibunacci(6));
        self::assertFalse(Fibunacci::isFibunacci(87));
    }

    /**
     * @testdox A fibunacci number can be returned by index
     * @covers phpOMS\Math\Functions\Fibunacci
     * @group framework
     */
    public function testFibunacciByKey() : void
    {
        self::assertEquals(1, Fibunacci::fib(1));
    }

    /**
     * @testdox The binet formula returns fibunacci numbers
     * @covers phpOMS\Math\Functions\Fibunacci
     * @group framework
     */
    public function testBinet() : void
    {
        self::assertTrue(Fibunacci::isFibunacci(Fibunacci::binet(3)));
        self::assertTrue(Fibunacci::isFibunacci(Fibunacci::binet(6)));
    }

    /**
     * @testdox The binet formula and the fibunacci formula return the same results
     * @covers phpOMS\Math\Functions\Fibunacci
     * @group framework
     */
    public function testBinetFib() : void
    {
        self::assertEquals(Fibunacci::binet(6), Fibunacci::fib(6));
        self::assertEquals(Fibunacci::binet(8), Fibunacci::fib(8));
    }
}
