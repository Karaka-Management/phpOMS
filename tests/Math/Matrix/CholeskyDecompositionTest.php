<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Matrix;

use phpOMS\Math\Matrix\CholeskyDecomposition;
use phpOMS\Math\Matrix\Matrix;
use phpOMS\Math\Matrix\Vector;

/**
 * @testdox phpOMS\tests\Math\Matrix\CholeskyDecompositionTest: Cholesky decomposition
 *
 * @internal
 */
class CholeskyDecompositionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The decomposition can be created and the original matrix can be computed
     * @covers phpOMS\Math\Matrix\CholeskyDecomposition
     * @group framework
     */
    public function testDecomposition() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $cholesky = new CholeskyDecomposition($A);

        self::assertEqualsWithDelta(
            $A->toArray(),
            $cholesky->getL()
                ->mult($cholesky->getL()->transpose())
                ->toArray(),
            0.2
        );
    }

    /**
     * @testdox The decomposition matrix has the expected values
     * @covers phpOMS\Math\Matrix\CholeskyDecomposition
     * @group framework
     */
    public function testL() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $cholesky = new CholeskyDecomposition($A);

        self::assertEqualsWithDelta([
            [5, 0, 0],
            [3, 3, 0],
            [-1, 1, 3],
        ], $cholesky->getL()->toArray(), 0.2);
    }

    /**
     * @testdox A matrix can be checked for symmetric positivity
     * @covers phpOMS\Math\Matrix\CholeskyDecomposition
     * @group framework
     */
    public function testSymmetricPositive() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $cholesky = new CholeskyDecomposition($A);

        self::assertTrue($cholesky->isSpd());

        $B = new Matrix();
        $B->setMatrix([
            [25, 15, 5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $choleskyB = new CholeskyDecomposition($B);

        self::assertTrue($choleskyB->isSpd());
    }

    /**
     * @testdox The equation Ax = b can be solved
     * @covers phpOMS\Math\Matrix\CholeskyDecomposition
     * @group framework
     */
    public function testSolve() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $cholesky = new CholeskyDecomposition($A);

        $vec = new Vector();
        $vec->setMatrix([[40], [49], [28]]);
        self::assertEqualsWithDelta([[1], [2], [3]], $cholesky->solve($vec)->toArray(), 0.2);
    }

    /**
     * @testdox A invalid vector throws a InvalidDimensionException
     * @covers phpOMS\Math\Matrix\CholeskyDecomposition
     * @group framework
     */
    public function testInvalidDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        $A = new Matrix();
        $A->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $cholesky = new CholeskyDecomposition($A);

        $vec = new Vector();
        $vec->setMatrix([[40], [49]]);
        $cholesky->solve($vec);
    }
}
