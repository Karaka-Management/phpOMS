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

namespace phpOMS\tests\Math\Optimization;

use phpOMS\Math\Optimization\Simplex;

/**
 * @testdox phpOMS\tests\Math\Optimization\SimplexTest: Numeric integration
 *
 * @internal
 *
 * Commented out assertions which take a long time with xdebug. without xdebug these are fine!
 */
final class SimplexTest extends \PHPUnit\Framework\TestCase
{
    public function testSimplex() : void
    {
        $simplex = new Simplex();
        self::assertEquals(
            [],
            $simplex->solve(
                [
                    [-1, 1],
                    [1, 1],
                    [1, -4],
                ],
                [8, -3, 2],
                [1, 3]
            )
        );
    }

    public function testSimplexBasicFeasible() : void
    {
        $simplex = new Simplex();
        self::assertEquals(
            [],
            $simplex->solve(
                [
                    [-1, 1],
                    [-2, -1],
                ],
                [1, 2],
                [5, -3]
            )
        );
    }

    public function testSimplexBasicInfeasible() : void
    {
        $simplex = new Simplex();
        self::assertEquals(
            [],
            $simplex->solve(
                [
                    [-1, 1],
                    [1, 1],
                    [1, -4],
                ],
                [8, -3, 2],
                [1, 3]
            )
        );
    }

    public function testSimplexLPInfeasible() : void
    {
        $simplex = new Simplex();
        self::assertEquals(
            [],
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
        self::assertEquals(
            [],
            $simplex->solve(
                [
                    [2, -1],
                    [1, 2],
                ],
                [-1, -2],
                [1, -1]
            )
        );
    }
}
