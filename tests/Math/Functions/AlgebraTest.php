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

namespace phpOMS\tests\Math\Functions;

use phpOMS\Math\Functions\Algebra;

/**
 * @testdox phpOMS\tests\Math\Functions\AlgebraTest: Various math functions
 *
 * @internal
 */
final class AlgebraTest extends \PHPUnit\Framework\TestCase
{
    public function testDotVectors() : void
    {
        self::assertEquals(
            3,
            Algebra::dot([1, 3, -5], [4, -2, -1])
        );
    }

    public function testDotMatrices() : void
    {
        self::assertEquals(
            [
                [58, 64],
                [139, 154],
            ],
            Algebra::dot(
                [
                    [1, 2, 3],
                    [4, 5, 6],
                ],
                [
                    [7, 8],
                    [9, 10],
                    [11, 12],
                ]
            )
        );
    }

    public function testDotVectorMatrix() : void
    {
        self::assertEquals(
            [11, 39, 53],
            Algebra::dot(
                [3, 4],
                [
                    [1, 5, 7],
                    [2, 6, 8],
                ]
            )
        );
    }

    public function testDotMatrixVector() : void
    {
        self::assertEquals(
            [11, 39, 53],
            Algebra::dot(
                [
                    [1, 2],
                    [5, 6],
                    [7, 8],
                ],
                [3, 4]
            )
        );
    }

    public function testCross3() : void
    {
        self::assertEquals(
            [-15, -2, 39],
            Algebra::cross3([3, -3, 1], [4, 9, 2])
        );
    }
}
