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

namespace phpOMS\tests\Math\Number;

use phpOMS\Math\Number\Prime;

/**
 * @testdox phpOMS\tests\Math\Number\PrimeTest: Prime number utilities
 *
 * @internal
 */
final class PrimeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A number can be checked to be a prime number
     * @covers \phpOMS\Math\Number\Prime
     * @group framework
     */
    public function testPrime() : void
    {
        self::assertTrue(Prime::isPrime(2));
        self::assertTrue(Prime::isPrime(997));
        self::assertFalse(Prime::isPrime(998));
    }

    /**
     * @testdox A prime number can be generated with the sieve of erathosthenes
     * @covers \phpOMS\Math\Number\Prime
     * @group framework
     */
    public function testSieve() : void
    {
        self::assertTrue(Prime::isPrime(Prime::sieveOfEratosthenes(12)[3]));
        self::assertTrue(Prime::isPrime(Prime::sieveOfEratosthenes(51)[7]));
    }

    /**
     * @testdox A number can be checked to be prime with the rabin test
     * @covers \phpOMS\Math\Number\Prime
     * @group framework
     */
    public function testRabin() : void
    {
        self::assertTrue(Prime::rabinTest(2));
        self::assertFalse(Prime::rabinTest(4));
        self::assertFalse(Prime::rabinTest(9));
        self::assertTrue(Prime::rabinTest(997));
        self::assertFalse(Prime::rabinTest(998));
    }

    /**
     * @testdox Mersenne numbers can be calculated
     * @covers \phpOMS\Math\Number\Prime
     * @group framework
     */
    public function testMersenne() : void
    {
        self::assertEquals(2047, Prime::mersenne(11));
        self::assertTrue(Prime::isMersenne(Prime::mersenne(2)));
        self::assertTrue(Prime::isMersenne(Prime::mersenne(4)));
    }

    /**
     * @testdox A number can be checked to be a mersenne number
     * @covers \phpOMS\Math\Number\Prime
     * @group framework
     */
    public function testIsMersenne() : void
    {
        self::assertTrue(Prime::isMersenne(8191));
        self::assertFalse(Prime::isMersenne(2046));
    }
}
