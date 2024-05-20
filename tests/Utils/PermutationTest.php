<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils;

use phpOMS\Utils\Permutation;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\Permutation::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\PermutationTest: Permutation utilities')]
final class PermutationTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An array can be permuted')]
    public function testPermuteAll() : void
    {
        $arr           = ['a', 'b', 'c'];
        $permutations  = ['abc', 'acb', 'bac', 'bca', 'cab', 'cba'];
        $permutations2 = [['a', 'b', 'c'], ['a', 'c', 'b'], ['b', 'a', 'c'], ['b', 'c', 'a'], ['c', 'a', 'b'], ['c', 'b', 'a']];

        self::assertEquals($permutations, Permutation::permuteAll($arr));
        self::assertEquals($permutations2, Permutation::permuteAll($arr, [], false));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Two string can be checked if they are a permutation of each other')]
    public function testIsPermutation() : void
    {
        self::assertTrue(Permutation::isPermutation('abc', 'bca'));
        self::assertFalse(Permutation::isPermutation('abc', 'bda'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A string can be checked if it is a palindrome')]
    public function testIsPalindrome() : void
    {
        self::assertTrue(Permutation::isPalindrome('abba'));
        self::assertTrue(Permutation::isPalindrome('abb1a', 'a-z'));
        self::assertFalse(Permutation::isPalindrome('abb1a'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An array can be permuted with a permutation key')]
    public function testPermuteBy() : void
    {
        self::assertEquals(['c', 'b', 'a'], Permutation::permuteByKey(['a', 'b', 'c'], [2, 1, 1]));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing permutation key throws a OutOfBoundsException')]
    public function testWrongPermuteKeyLength() : void
    {
        $this->expectException(\OutOfBoundsException::class);

        Permutation::permuteByKey('abc', [2, 1, 1, 6]);
    }
}
