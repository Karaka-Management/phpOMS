<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Math\Functions;

use phpOMS\Math\Functions\Fibunacci;

class FibunacciTest extends \PHPUnit\Framework\TestCase
{
    public function testFibunacci()
    {
        self::assertTrue(Fibunacci::isFibunacci(13));
        self::assertTrue(Fibunacci::isFibunacci(55));
        self::assertTrue(Fibunacci::isFibunacci(89));
        self::assertFalse(Fibunacci::isFibunacci(6));
        self::assertFalse(Fibunacci::isFibunacci(87));

        self::assertEquals(1, Fibunacci::fib(1));
        self::assertTrue(Fibunacci::isFibunacci(Fibunacci::binet(3)));
        self::assertTrue(Fibunacci::isFibunacci(Fibunacci::binet(6)));

        self::assertEquals(Fibunacci::binet(6), Fibunacci::fib(6));
        self::assertEquals(Fibunacci::binet(8), Fibunacci::fib(8));
    }
}
