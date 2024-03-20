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

use phpOMS\Math\Number\Prime;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Number\Prime::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Number\PrimeTest: Prime number utilities')]
final class PrimeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A number can be checked to be a prime number')]
    public function testPrime() : void
    {
        self::assertTrue(Prime::isPrime(2));
        self::assertTrue(Prime::isPrime(997));
        self::assertFalse(Prime::isPrime(998));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A prime number can be generated with the sieve of erathosthenes')]
    public function testSieve() : void
    {
        self::assertTrue(Prime::isPrime(Prime::sieveOfEratosthenes(12)[3]));
        self::assertTrue(Prime::isPrime(Prime::sieveOfEratosthenes(51)[7]));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A number can be checked to be prime with the rabin test')]
    public function testRabin() : void
    {
        self::assertTrue(Prime::rabinTest(2));
        self::assertFalse(Prime::rabinTest(4));
        self::assertFalse(Prime::rabinTest(9));
        self::assertTrue(Prime::rabinTest(997));
        self::assertFalse(Prime::rabinTest(998));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Mersenne numbers can be calculated')]
    public function testMersenne() : void
    {
        self::assertEquals(2047, Prime::mersenne(11));
        self::assertTrue(Prime::isMersenne(Prime::mersenne(2)));
        self::assertTrue(Prime::isMersenne(Prime::mersenne(4)));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A number can be checked to be a mersenne number')]
    public function testIsMersenne() : void
    {
        self::assertTrue(Prime::isMersenne(8191));
        self::assertFalse(Prime::isMersenne(2046));
    }
}
