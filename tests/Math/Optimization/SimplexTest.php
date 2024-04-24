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

namespace phpOMS\tests\Math\Optimization;

use phpOMS\Math\Optimization\Simplex;

/**
 * @internal
 *
 * Commented out assertions which take a long time with xdebug. without xdebug these are fine!
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Optimization\SimplexTest: Numeric integration')]
final class SimplexTest extends \PHPUnit\Framework\TestCase
{
    public function testSimplexBasicInfeasible() : void
    {
        $simplex = new Simplex();
        self::assertEqualsWithDelta(
            [
                [11.333333, 3.333333, 0.0, 11.666667, 0.0],
                21.333333,
            ],
            $simplex->solve(
                [
                    [-1, 1],
                    [1, 1],
                    [1, -4],
                ],
                [8, -3, 2],
                [1, 3]
            ),
            0.01
        );
    }

    public function testSimplexBasicFeasible() : void
    {
        $simplex = new Simplex();
        self::assertEqualsWithDelta(
            [
                [1.0, 0.0, 0.0, 0.0],
                5.0,
            ],
            $simplex->solve(
                [
                    [-1, 1],
                    [-2, -1],
                ],
                [1, 2],
                [5, -3]
            ),
            0.0
        );
    }

    public function testSimplexLPInfeasible() : void
    {
        $simplex = new Simplex();
        self::assertEquals(
            [
                [-2, -2, -2, -2, -2],
                \INF,
            ],
            $simplex->solve(
                [
                    [-1, -1],
                    [2, 2],
                ],
                [2, -10],
                [3, -2]
            )
        );
    }

    public function testSimplexLPUnbound() : void
    {
        $simplex = new Simplex();
        self::assertEqualsWithDelta(
            [
                [-1, -1, -1, -1],
                \INF,
            ],
            $simplex->solve(
                [
                    [2, -1],
                    [1, 2],
                ],
                [-1, -2],
                [1, -1]
            ),
            0.01
        );
    }
}
