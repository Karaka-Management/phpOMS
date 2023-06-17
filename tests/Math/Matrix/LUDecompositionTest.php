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

namespace phpOMS\tests\Math\Matrix;

use phpOMS\Math\Matrix\LUDecomposition;
use phpOMS\Math\Matrix\Matrix;
use phpOMS\Math\Matrix\Vector;

/**
 * @testdox phpOMS\tests\Math\Matrix\LUDecompositionTest: LU decomposition
 *
 * @internal
 */
final class LUDecompositionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The L matrix of the decomposition can be calculated
     * @covers phpOMS\Math\Matrix\LUDecomposition
     * @group framework
     */
    public function testL() : void
    {
        $B = new Matrix();
        $B->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $lu = new LUDecomposition($B);

        self::assertEqualsWithDelta([
            [1, 0, 0],
            [0.6, 1, 0],
            [-0.2, 0.375, 1],
        ], $lu->getL()->toArray(), 0.2);
    }

    /**
     * @testdox The U matrix of the decomposition can be calculated
     * @covers phpOMS\Math\Matrix\LUDecomposition
     * @group framework
     */
    public function testU() : void
    {
        $B = new Matrix();
        $B->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $lu = new LUDecomposition($B);

        self::assertEqualsWithDelta([
            [25, 15, -5],
            [0, 8, 3],
            [0, 0, 8.875],
        ], $lu->getU()->toArray(), 0.2);
    }

    /**
     * @testdox The matrix can be checked for singularity
     * @covers phpOMS\Math\Matrix\LUDecomposition
     * @group framework
     */
    public function testSingularity() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $lu = new LUDecomposition($A);

        self::assertTrue($lu->isNonSingular());

        $B = new Matrix();
        $B->setMatrix([
            [25, 15, -5],
            [0, 0, 1],
            [0, 0, 2],
        ]);

        $luB = new LUDecomposition($B);

        self::assertFalse($luB->isNonSingular());
    }

    /**
     * @testdox The equation Ax = b can be solved for a none-singular matrix
     * @covers phpOMS\Math\Matrix\LUDecomposition
     * @group framework
     */
    public function testSolve() : void
    {
        $B = new Matrix();
        $B->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $lu = new LUDecomposition($B);

        $vec = new Vector();
        $vec->setMatrix([[40], [49], [28]]);
        self::assertEqualsWithDelta([[1], [2], [3]], $lu->solve($vec)->toArray(), 0.2);
    }

    /**
     * @testdox The pivots of the decomposition can be calculated
     * @covers phpOMS\Math\Matrix\LUDecomposition
     * @group framework
     */
    public function testPivot() : void
    {
        $B = new Matrix();
        $B->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $lu = new LUDecomposition($B);

        self::assertEquals([0, 1, 2], $lu->getPivot());
    }

    /**
     * @testdox The equation Ax = b can be solved for a singular matrix
     * @covers phpOMS\Math\Matrix\LUDecomposition
     * @group framework
     */
    public function testSolveOfSingularMatrix() : void
    {
        $this->expectException(\Exception::class);

        $B = new Matrix();
        $B->setMatrix([
            [25, 15, -5],
            [0, 0, 1],
            [0, 0, 2],
        ]);

        $lu = new LUDecomposition($B);

        $vec = new Vector();
        $vec->setMatrix([[40], [49], [28]]);

        $lu->solve($vec);
    }

    /**
     * @testdox The decomposition can be created and the original matrix can be computed
     * @covers phpOMS\Math\Matrix\LUDecomposition
     * @group framework
     */
    public function testComposition() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $lu = new LUDecomposition($A);

        self::assertEqualsWithDelta(
            $A->toArray(),
            $lu->getL()
                ->mult($lu->getU())
                ->toArray(),
            0.2
        );
    }

    /**
     * @testdox The determinat can be calculated
     * @covers phpOMS\Math\Matrix\LUDecomposition
     * @group framework
     */
    public function testDet() : void
    {
        $B = new Matrix();
        $B->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $lu = new LUDecomposition($B);
        self::assertEqualsWithDelta(1775.0, $lu->det(), 0.1);
    }

    /**
     * @testdox A invalid vector throws a InvalidDimensionException
     * @covers phpOMS\Math\Matrix\LUDecomposition
     * @group framework
     */
    public function testInvalidDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        $B = new Matrix();
        $B->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $lu  = new LUDecomposition($B);
        $vec = new Vector();
        $vec->setMatrix([[40], [49]]);

        $lu->solve($vec);
    }
}
