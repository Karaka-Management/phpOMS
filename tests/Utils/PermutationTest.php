<?php
/**
 * Karaka
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

namespace phpOMS\tests\Utils;

use phpOMS\Utils\Permutation;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @testdox phpOMS\tests\Utils\PermutationTest: Permutation utilities
 *
 * @internal
 */
final class PermutationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox An array can be permuted
     * @covers phpOMS\Utils\Permutation
     * @group framework
     */
    public function testPermute() : void
    {
        $arr           = ['a', 'b', 'c'];
        $permutations  = ['abc', 'acb', 'bac', 'bca', 'cab', 'cba'];
        $permutations2 = [['a', 'b', 'c'], ['a', 'c', 'b'], ['b', 'a', 'c'], ['b', 'c', 'a'], ['c', 'a', 'b'], ['c', 'b', 'a']];

        self::assertEquals($permutations, Permutation::permut($arr));
        self::assertEquals($permutations2, Permutation::permut($arr, [], false));
    }

    /**
     * @testdox Two string can be checked if they are a permutation of each other
     * @covers phpOMS\Utils\Permutation
     * @group framework
     */
    public function testIsPermutation() : void
    {
        self::assertTrue(Permutation::isPermutation('abc', 'bca'));
        self::assertFalse(Permutation::isPermutation('abc', 'bda'));
    }

    /**
     * @testdox A string can be checked if it is a palindrome
     * @covers phpOMS\Utils\Permutation
     * @group framework
     */
    public function testIsPalindrome() : void
    {
        self::assertTrue(Permutation::isPalindrome('abba'));
        self::assertTrue(Permutation::isPalindrome('abb1a', 'a-z'));
        self::assertFalse(Permutation::isPalindrome('abb1a'));
    }

    /**
     * @testdox An array can be permuted with a permutation key
     * @covers phpOMS\Utils\Permutation
     * @group framework
     */
    public function testPermutate() : void
    {
        self::assertEquals(['c', 'b', 'a'], Permutation::permutate(['a', 'b', 'c'], [2, 1, 1]));
    }

    /**
     * @testdox A none-existing permutation key throws a OutOfBoundsException
     * @covers phpOMS\Utils\Permutation
     * @group framework
     */
    public function testWrongPermuteKeyLength() : void
    {
        $this->expectException(\OutOfBoundsException::class);

        Permutation::permutate('abc', [2, 1, 1, 6]);
    }
}
