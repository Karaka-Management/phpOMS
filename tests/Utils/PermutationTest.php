<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
 declare(strict_types=1);

namespace phpOMS\tests\Utils;

use phpOMS\Utils\Permutation;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
class PermutationTest extends \PHPUnit\Framework\TestCase
{
    public function testPermute() : void
    {
        $arr           = ['a', 'b', 'c'];
        $permutations  = ['abc', 'acb', 'bac', 'bca', 'cab', 'cba'];
        $permutations2 = [['a', 'b', 'c'], ['a', 'c', 'b'], ['b', 'a', 'c'], ['b', 'c', 'a'], ['c', 'a', 'b'], ['c', 'b', 'a']];

        self::assertEquals($permutations, Permutation::permut($arr));
        self::assertEquals($permutations2, Permutation::permut($arr, [], false));
    }

    public function testIsPermutation() : void
    {
        self::assertTrue(Permutation::isPermutation('abc', 'bca'));
        self::assertFalse(Permutation::isPermutation('abc', 'bda'));
    }

    public function testIsPalindrome() : void
    {
        self::assertTrue(Permutation::isPalindrome('abba'));
        self::assertTrue(Permutation::isPalindrome('abb1a', 'a-z'));
        self::assertFalse(Permutation::isPalindrome('abb1a'));
    }

    public function testPermutate() : void
    {
        self::assertEquals(['c', 'b', 'a'], Permutation::permutate(['a', 'b', 'c'], [2, 1, 1]));
    }

    public function testWrongPermuteParameterType() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Permutation::permutate(4, [2, 1, 1]);
    }

    public function testWrongPermuteKeyLength() : void
    {
        self::expectException(\OutOfBoundsException::class);

        Permutation::permutate('abc', [2, 1, 1, 6]);
    }
}
