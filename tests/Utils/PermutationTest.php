<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Utils;

use phpOMS\Utils\Permutation;

require_once __DIR__ . '/../Autoloader.php';

class PermutationTest extends \PHPUnit\Framework\TestCase
{
    public function testPermute()
    {
        $arr          = ['a', 'b', 'c'];
        $permutations = ['abc', 'acb', 'bac', 'bca', 'cab', 'cba'];

        self::assertEquals($permutations, Permutation::permut($arr));
    }

    public function testIsPermutation()
    {
        self::assertTrue(Permutation::isPermutation('abc', 'bca'));
        self::assertFalse(Permutation::isPermutation('abc', 'bda'));
    }

    public function testIsPalindrome()
    {
        self::assertTrue(Permutation::isPalindrome('abba'));
        self::assertTrue(Permutation::isPalindrome('abb1a', 'a-z'));
        self::assertFalse(Permutation::isPalindrome('abb1a'));
    }

    public function testPermutate()
    {
        self::assertEquals(['c', 'b', 'a'], Permutation::permutate(['a', 'b', 'c'], [2, 1, 1]));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongPermuteParameterType()
    {
        Permutation::permutate(4, [2, 1, 1]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongPermuteKeyLength()
    {
        Permutation::permutate('abc', [2, 1, 1, 6]);
    }
}
