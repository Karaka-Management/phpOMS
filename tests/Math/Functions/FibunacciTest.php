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
 * @internal
 */
class FibunacciTest extends \PHPUnit\Framework\TestCase
{
    public function testFibunacci() : void
    {
        self::assertTrue(Fibunacci::isFibunacci(13));
        self::assertTrue(Fibunacci::isFibunacci(55));
        self::assertTrue(Fibunacci::isFibunacci(89));
        self::assertFalse(Fibunacci::isFibunacci(6));
        self::assertFalse(Fibunacci::isFibunacci(87));
    }

    public function testFibunacciByKey() : void
    {
        self::assertEquals(1, Fibunacci::fib(1));
    }

    public function testIsFibunacci() : void
    {
        self::assertTrue(Fibunacci::isFibunacci(Fibunacci::binet(3)));
        self::assertTrue(Fibunacci::isFibunacci(Fibunacci::binet(6)));
    }

    public function testBinet() : void
    {
        self::assertEquals(Fibunacci::binet(6), Fibunacci::fib(6));
        self::assertEquals(Fibunacci::binet(8), Fibunacci::fib(8));
    }
}
