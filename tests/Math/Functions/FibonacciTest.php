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

namespace phpOMS\tests\Math\Functions;

use phpOMS\Math\Functions\Fibonacci;

/**
 * @testdox phpOMS\tests\Math\Functions\FibonacciTest: Fibonacci functions
 *
 * @internal
 */
class FibonacciTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A number can be checked if it is a fibonacci number
     * @covers phpOMS\Math\Functions\Fibonacci
     * @group framework
     */
    public function testFibonacci() : void
    {
        self::assertTrue(Fibonacci::isFibonacci(13));
        self::assertTrue(Fibonacci::isFibonacci(55));
        self::assertTrue(Fibonacci::isFibonacci(89));
        self::assertFalse(Fibonacci::isFibonacci(6));
        self::assertFalse(Fibonacci::isFibonacci(87));
    }

    /**
     * @testdox A fibonacci number can be returned by index
     * @covers phpOMS\Math\Functions\Fibonacci
     * @group framework
     */
    public function testFibonacciByKey() : void
    {
        self::assertEquals(1, Fibonacci::fib(1));
    }

    /**
     * @testdox The binet formula returns fibonacci numbers
     * @covers phpOMS\Math\Functions\Fibonacci
     * @group framework
     */
    public function testBinet() : void
    {
        self::assertTrue(Fibonacci::isFibonacci(Fibonacci::binet(3)));
        self::assertTrue(Fibonacci::isFibonacci(Fibonacci::binet(6)));
    }

    /**
     * @testdox The binet formula and the fibonacci formula return the same results
     * @covers phpOMS\Math\Functions\Fibonacci
     * @group framework
     */
    public function testBinetFib() : void
    {
        self::assertEquals(Fibonacci::binet(6), Fibonacci::fib(6));
        self::assertEquals(Fibonacci::binet(8), Fibonacci::fib(8));
    }
}
