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

namespace phpOMS\tests\Math\Functions;

use phpOMS\Math\Functions\Fibonacci;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Functions\Fibonacci::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Functions\FibonacciTest: Fibonacci functions')]
final class FibonacciTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A number can be checked if it is a fibonacci number')]
    public function testFibonacci() : void
    {
        self::assertTrue(Fibonacci::isFibonacci(13));
        self::assertTrue(Fibonacci::isFibonacci(55));
        self::assertTrue(Fibonacci::isFibonacci(89));
        self::assertFalse(Fibonacci::isFibonacci(6));
        self::assertFalse(Fibonacci::isFibonacci(87));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A fibonacci number can be returned by index')]
    public function testFibonacciByKey() : void
    {
        self::assertEquals(1, Fibonacci::fib(1));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The binet formula returns fibonacci numbers')]
    public function testBinet() : void
    {
        self::assertTrue(Fibonacci::isFibonacci(Fibonacci::binet(3)));
        self::assertTrue(Fibonacci::isFibonacci(Fibonacci::binet(6)));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The binet formula and the fibonacci formula return the same results')]
    public function testBinetFib() : void
    {
        self::assertEquals(Fibonacci::binet(6), Fibonacci::fib(6));
        self::assertEquals(Fibonacci::binet(8), Fibonacci::fib(8));
    }
}
