<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Math\Number;

use phpOMS\Math\Number\Prime;

/**
 * @internal
 */
class PrimeTest extends \PHPUnit\Framework\TestCase
{
    public function testPrime() : void
    {
        self::assertTrue(Prime::isPrime(2));
        self::assertTrue(Prime::isPrime(997));
        self::assertFalse(Prime::isPrime(998));
    }

    public function testSieve() : void
    {
        self::assertTrue(Prime::isPrime(Prime::sieveOfEratosthenes(12)[3]));
        self::assertTrue(Prime::isPrime(Prime::sieveOfEratosthenes(51)[7]));
    }

    public function testRabin() : void
    {
        self::assertTrue(Prime::rabinTest(2));
        self::assertFalse(Prime::rabinTest(4));
        self::assertFalse(Prime::rabinTest(9));
        self::assertTrue(Prime::rabinTest(997));
        self::assertFalse(Prime::rabinTest(998));
    }

    public function testMersenne() : void
    {
        self::assertEquals(2047, Prime::mersenne(11));
        self::assertTrue(Prime::isMersenne(Prime::mersenne(2)));
        self::assertTrue(Prime::isMersenne(Prime::mersenne(4)));
        self::assertFalse(Prime::isMersenne(2046));
    }
}
