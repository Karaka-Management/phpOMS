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

use phpOMS\Utils\ArrayUtils;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @testdox phpOMS\tests\Utils\ArrayUtilsTest: Array utilities
 *
 * @internal
 */
final class ArrayUtilsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Array values can be set and returned with a path
     * @covers phpOMS\Utils\ArrayUtils
     * @group framework
     */
    public function testArrayInputOutput() : void
    {
        $expected = [
            'a' => [
                'aa' => 1,
                'ab' => [
                    'aba',
                    'ab0',
                    [
                        3,
                        'c',
                    ],
                    4,
                ],
            ],
            2 => '2a',
        ];

        $actual = [];
        $actual = ArrayUtils::setArray('a/aa', $actual, 1, '/');
        $actual = ArrayUtils::setArray('a/ab', $actual, ['aba'], '/');
        $actual = ArrayUtils::setArray('a/ab', $actual, 'abb', '/');
        $actual = ArrayUtils::setArray('2', $actual, '2a', '/');
        $actual = ArrayUtils::setArray('a/ab/1', $actual, 'ab0', '/', true);
        $actual = ArrayUtils::setArray('a/ab', $actual, [3, 4], '/');
        $actual = ArrayUtils::setArray('a/ab/2', $actual, 'c', '/');

        self::assertEquals($expected, $actual);
        self::assertEquals('ab0', ArrayUtils::getArray('a/ab/1', $expected));
    }

    /**
     * @testdox A invalid array path returns null
     * @covers phpOMS\Utils\ArrayUtils
     * @group framework
     */
    public function testArrayInvalidArrayPath() : void
    {
        $expected = [
            'a' => [
                'aa' => 1,
                'ab' => [
                    'aba',
                    'ab0',
                    [
                        3,
                        'c',
                    ],
                    4,
                ],
            ],
            2 => '2a',
        ];

        self::assertNull(ArrayUtils::getArray('a/zzz/1', $expected));
    }

    /**
     * @testdox Test recursively if a value is in an array
     * @covers phpOMS\Utils\ArrayUtils
     * @group framework
     */
    public function testArrayInRecursive() : void
    {
        $expected = [
            'a' => [
                'aa' => 1,
                'ab' => [
                    'aba',
                    'ab0',
                ],
            ],
            2 => '2a',
        ];

        self::assertTrue(ArrayUtils::inArrayRecursive('aba', $expected));
        self::assertTrue(ArrayUtils::inArrayRecursive('2a', $expected));
        self::assertTrue(ArrayUtils::inArrayRecursive('2a', $expected, 2));
        self::assertFalse(ArrayUtils::inArrayRecursive('2a', $expected, 3));
    }

    /**
     * @testdox An array element can be removed by its path
     * @covers phpOMS\Utils\ArrayUtils
     * @group framework
     */
    public function testArrayDelete() : void
    {
        $expected = [
            'a' => [
                'aa' => 1,
                'ab' => [
                    'aba',
                    'ab0',
                ],
            ],
            2 => '2a',
        ];

        self::assertFalse(ArrayUtils::inArrayRecursive('aba', ArrayUtils::unsetArray('a/ab', $expected, '/')));
    }

    /**
     * @testdox Deleting an invalid array path returns the original array
     * @covers phpOMS\Utils\ArrayUtils
     * @group framework
     */
    public function testInvalidArrayDelete() : void
    {
        $expected = [
            'a' => [
                'aa' => 1,
                'ab' => [
                    'aba',
                    'ab0',
                ],
            ],
            2 => '2a',
        ];

        self::assertEquals($expected, ArrayUtils::unsetArray('a/zzz', $expected, '/'));
    }

    /**
     * @testdox The recursive sum of all values in an array can be calculated
     * @covers phpOMS\Utils\ArrayUtils
     * @group framework
     */
    public function testArrayRecursiveSum() : void
    {
        $numArrRec = [1, [2, [3, 4]]];
        self::assertEquals(10, ArrayUtils::arraySumRecursive($numArrRec));
    }

    /**
     * @testdox A multi-dimensional array can be flatten to a one-dimensional array
     * @covers phpOMS\Utils\ArrayUtils
     * @group framework
     */
    public function testArrayFlatten() : void
    {
        $numArr    = [1, 2, 3, 4];
        $numArrRec = [1, [2, [3, 4]]];
        self::assertEquals($numArr, ArrayUtils::arrayFlatten($numArrRec));
    }

    /**
     * @testdox The sum of an array can be calculated
     * @covers phpOMS\Utils\ArrayUtils
     * @group framework
     */
    public function testArraySum() : void
    {
        $numArr = [1, 2, 3, 4];
        self::assertEquals(10, ArrayUtils::arraySum($numArr));
        self::assertEquals(9, ArrayUtils::arraySum($numArr, 1));
        self::assertEquals(5, ArrayUtils::arraySum($numArr, 1, 2));
    }

    /**
     * @testdox An array can be checked if it contains multiple defined elements
     * @covers phpOMS\Utils\ArrayUtils
     * @group framework
     */
    public function testArrayAllIn() : void
    {
        $numArr = [1, 2, 3, 4];
        self::assertTrue(ArrayUtils::allInArray([], $numArr));
        self::assertTrue(ArrayUtils::allInArray([1, 3, 4], $numArr));
        self::assertTrue(ArrayUtils::allInArray([1, 2, 3, 4], $numArr));
        self::assertFalse(ArrayUtils::allInArray([1, 5, 3], $numArr));
    }

    /**
     * @testdox An array can be checked if it contains any of the defined elements
     * @covers phpOMS\Utils\ArrayUtils
     * @group framework
     */
    public function testArrayAnyIn() : void
    {
        $numArr = [1, 2, 3, 4];
        self::assertTrue(ArrayUtils::anyInArray($numArr, [2, 6, 8]));
        self::assertFalse(ArrayUtils::anyInArray($numArr, [10, 22]));
    }

    /**
     * @testdox An array can be turned into a csv string
     * @covers phpOMS\Utils\ArrayUtils
     * @group framework
     */
    public function testArrayToCsv() : void
    {
        $csvArr = [
            ['Title1', 'Title2', 'Title3', 'Title4'],
            [1, 1.2, true, 'test'],
            [2, 3.2, false, 'test2'],
        ];

        self::assertEquals(
            "Title1;Title2;Title3;Title4\n"
            . "1;1.2;1;test\n"
            . "2;3.2;;test2\n",
            ArrayUtils::arrayToCsv($csvArr));
    }

    /**
     * @testdox An array can be checked if it has an element and returns its index
     * @covers phpOMS\Utils\ArrayUtils
     * @group framework
     */
    public function testArgHas() : void
    {
        if (ArrayUtils::getArg('--configuration', $_SERVER['argv'] ?? []) !== null) {
            self::assertGreaterThan(0, ArrayUtils::hasArg('--configuration', $_SERVER['argv'] ?? []));
        }
    }

    /**
     * @testdox A none-existing argument in an array returns a negative value
     * @covers phpOMS\Utils\ArrayUtils
     * @group framework
     */
    public function testInvalidArgHas() : void
    {
        self::assertEquals(-1, ArrayUtils::hasArg('--testNull', $_SERVER['argv'] ?? []));
    }

    /**
     * @testdox The argument value in an array can be returned
     * @covers phpOMS\Utils\ArrayUtils
     * @group framework
     */
    public function testArgGet() : void
    {
        if (ArrayUtils::getArg('--configuration', $_SERVER['argv'] ?? []) !== null) {
            self::assertTrue(\stripos(ArrayUtils::getArg('--configuration', $_SERVER['argv'] ?? []), '.xml') !== false);
        }
    }

    /**
     * @testdox A none-existing argument in an array returns null
     * @covers phpOMS\Utils\ArrayUtils
     * @group framework
     */
    public function testInvalidArgGet() : void
    {
        self::assertNull(ArrayUtils::getArg('--testNull', $_SERVER['argv'] ?? []));
    }

    /**
     * @testdox All array values in an array can be potentiated by a numeric value
     * @covers phpOMS\Utils\ArrayUtils
     *
     * @group framework
     */
    public function test() : void
    {
        self::assertEquals([4, 9, 16], ArrayUtils::power([2, 3, 4], 2));
        self::assertEquals([8, 27, 64], ArrayUtils::power([2, 3, 4], 3));

        self::assertEqualsWithDelta([2.0, 3.0, 4.0], ArrayUtils::power([4, 9, 16], 1 / 2), 0.01);
        self::assertEqualsWithDelta([2.0, 3.0, 4.0], ArrayUtils::power([8, 27, 64], 1 / 3), 0.01);
    }

    /**
     * @testdox All array values in an array can be square rooted
     * @covers phpOMS\Utils\ArrayUtils
     * @group framework
     */
    public function testSqrt() : void
    {
        self::assertEqualsWithDelta([2, 3, 4], ArrayUtils::sqrt([4, 9, 16]), 0.01);
    }

    /**
     * @testdox All array values in an array can be turned into their absolute value
     * @covers phpOMS\Utils\ArrayUtils
     * @group framework
     */
    public function testAbs() : void
    {
        self::assertEquals([1, 3, 4], ArrayUtils::abs([-1, 3, -4]));
    }

    /**
     * @testdox The difference of two arrays can be evaluated recursively
     * @covers phpOMS\Utils\ArrayUtils
     * @group framework
     */
    public function testArrayDiffAssocResursive() : void
    {
        self::assertEquals(
            ['a' => 1, 'b' => ['c' => 2]],
            ArrayUtils::array_diff_assoc_recursive(['a' => 1, 'b' => ['c' => 2]], [])
        );

        self::assertEquals(
            ['b' => ['d' => 3]],
            ArrayUtils::array_diff_assoc_recursive(['a' => 1, 'b' => ['c' => 2, 'd' => 3]], ['a' => 1, 'b' => ['c' => 2]])
        );

        self::assertEquals(
            [],
            ArrayUtils::array_diff_assoc_recursive([], ['a' => 1, 'b' => ['c' => 2]])
        );
    }
}
