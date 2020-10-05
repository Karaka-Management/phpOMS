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

use phpOMS\Math\Matrix\EigenvalueDecomposition;
use phpOMS\Math\Matrix\Matrix;

/**
 * @testdox phpOMS\tests\Math\Matrix\EigenvalueDecompositionTest: Eigenvalue decomposition
 *
 * @internal
 */
class EigenvalueDecompositionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A matrix can be checked for symmetry
     * @covers phpOMS\Math\Matrix\EigenvalueDecomposition
     * @group framework
     */
    public function testSymmetricSymmetryMatrix() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [3, 1, 1],
            [1, 2, 2],
            [1, 2, 2],
        ]);

        $eig = new EigenvalueDecomposition($A);

        self::assertTrue($eig->isSymmetric());

        $B = new Matrix();
        $B->setMatrix([
            [3, 1, 2],
            [1, 2, 2],
            [1, 2, 2],
        ]);

        $eigB = new EigenvalueDecomposition($B);

        self::assertFalse($eigB->isSymmetric());
    }

    /**
     * @testdox The eigenvalues can be calculated for a symmetric matrix
     * @covers phpOMS\Math\Matrix\EigenvalueDecomposition
     * @group framework
     */
    public function testSymmetricMatrixEigenvalues() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [3, 1, 1],
            [1, 2, 2],
            [1, 2, 2],
        ]);

        $eig = new EigenvalueDecomposition($A);

        self::assertEqualsWithDelta([0, 2, 5], $eig->getRealEigenvalues()->toArray(), 0.1);
        self::assertEqualsWithDelta([0, 0, 0], $eig->getImagEigenvalues()->toArray(), 0.1);
    }

    /**
     * @testdox The V matrix of the decomposition can be calculated for a symmetric matrix
     * @covers phpOMS\Math\Matrix\EigenvalueDecomposition
     * @group framework
     */
    public function testSymmetricMatrixV() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [3, 1, 1],
            [1, 2, 2],
            [1, 2, 2],
        ]);

        $eig = new EigenvalueDecomposition($A);

        self::assertEqualsWithDelta([
            [0, 2 / \sqrt(6), 1 / \sqrt(3)],
            [1 / \sqrt(2), -1 / \sqrt(6), 1 / \sqrt(3)],
            [-1 / \sqrt(2), -1 / \sqrt(6), 1 / \sqrt(3)],
        ], $eig->getV()->toArray(), 0.2);
    }

    /**
     * @testdox The D matrix of the decomposition can be calculated for a symmetric matrix
     * @covers phpOMS\Math\Matrix\EigenvalueDecomposition
     * @group framework
     */
    public function testSymmetricMatrixD() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [3, 1, 1],
            [1, 2, 2],
            [1, 2, 2],
        ]);

        $eig = new EigenvalueDecomposition($A);

        self::assertEqualsWithDelta([
            [0, 0, 0],
            [0, 2, 0],
            [0, 0, 5],
        ], $eig->getD()->toArray(), 0.2);
    }

    /**
     * @testdox The eigenvalues can be calculated for a none-symmetric matrix
     * @covers phpOMS\Math\Matrix\EigenvalueDecomposition
     * @group framework
     */
    public function testNonSymmetricMatrixEigenvalues() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [-2, -4, 2],
            [-2, 1, 2],
            [4, 2, 5],
        ]);

        $eig = new EigenvalueDecomposition($A);

        self::assertEqualsWithDelta([-5, 3, 6], $eig->getRealEigenvalues()->toArray(), 0.1);
        self::assertEqualsWithDelta([0, 0, 0], $eig->getImagEigenvalues()->toArray(), 0.1);
    }

    /**
     * @testdox The V matrix of the decomposition can be calculated for a none-symmetric matrix
     * @covers phpOMS\Math\Matrix\EigenvalueDecomposition
     * @group framework
     */
    public function testNonSymmetricMatrixV() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [-2, -4, 2],
            [-2, 1, 2],
            [4, 2, 5],
        ]);

        $eig = new EigenvalueDecomposition($A);

        self::assertEqualsWithDelta([
            [-\sqrt(2 / 3), \sqrt(2 / 7), -1 / \sqrt(293)],
            [-1 / \sqrt(6), -3 / \sqrt(14), -6 / \sqrt(293)],
            [1 / \sqrt(6), -1 / \sqrt(14), -16 / \sqrt(293)],
        ], $eig->getV()->toArray(), 0.2);
    }

    /**
     * @testdox The D matrix of the decomposition can be calculated for a none-symmetric matrix
     * @covers phpOMS\Math\Matrix\EigenvalueDecomposition
     * @group framework
     */
    public function testNonSymmetricMatrixD() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [-2, -4, 2],
            [-2, 1, 2],
            [4, 2, 5],
        ]);

        $eig = new EigenvalueDecomposition($A);

        self::assertEqualsWithDelta([
            [-5, 0, 0],
            [0, 3, 0],
            [0, 0, 6],
        ], $eig->getD()->toArray(), 0.2);
    }

    /**
     * @testdox The decomposition can be created and the original matrix can be computed for a symmetric matrix
     * @covers phpOMS\Math\Matrix\EigenvalueDecomposition
     * @group framework
     */
    public function testCompositeSymmetric() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [3, 1, 1],
            [1, 2, 2],
            [1, 2, 2],
        ]);

        $eig = new EigenvalueDecomposition($A);

        self::assertEqualsWithDelta(
            $A->toArray(),
            $eig->getV()
                ->mult($eig->getD())
                ->mult($eig->getV()->transpose())
                ->toArray()
        , 0.2);
    }

    /**
     * @testdox The decomposition can be created and the original matrix can be computed for a none-symmetric matrix
     * @covers phpOMS\Math\Matrix\EigenvalueDecomposition
     * @group framework
     */
    public function testCompositeNonSymmetric() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [-2, -4, 2],
            [-2, 1, 2],
            [4, 2, 5],
        ]);

        $eig = new EigenvalueDecomposition($A);

        self::assertEqualsWithDelta(
            $A->toArray(),
            $eig->getV()
                ->mult($eig->getD())
                ->mult($eig->getV()->inverse())
                ->toArray(),
            0.2
        );
    }

    public function testComplexEigenvalueDecomposition() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [3, -2],
            [4, -1],
        ]);

        $eig = new EigenvalueDecomposition($A);
        self::assertEqualsWithDelta([
            [1, 2],
            [-2, 1],
        ], $eig->getD()->toArray(), 0.1);

        self::assertEqualsWithDelta([1, 1], $eig->getRealEigenvalues()->toArray(), 0.1);
        self::assertEqualsWithDelta([2, -2], $eig->getImagEigenvalues()->toArray(), 0.1);
    }

    public function testComplexDivision() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [-2, -4, 2],
            [3, 1, -4],
            [4, 5, 5],
        ]);

        $eig = new EigenvalueDecomposition($A);
        self::assertEqualsWithDelta([
            [-0.3569, 4.49865, 0.0],
            [-4.49865, -0.3569, 0],
            [0.0, 0.0, 4.7139],
        ], $eig->getD()->toArray(), 0.1);

        self::assertEqualsWithDelta([-0.35695, -0.35695, 4.7139], $eig->getRealEigenvalues()->toArray(), 0.1);
        self::assertEqualsWithDelta([4.49865, -4.49865, 0.0], $eig->getImagEigenvalues()->toArray(), 0.1);
    }

    public function testComplexDivision2() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [-2, 3, 2],
            [-4, 1, -4],
            [4, 5, 5],
        ]);

        $eig = new EigenvalueDecomposition($A);
        self::assertEqualsWithDelta([
            [-2.5510, 0.0, 0.0],
            [0.0, 3.27552, 4.79404],
            [0.0, -4.7940, 3.27552],
        ], $eig->getD()->toArray(), 0.1);

        self::assertEqualsWithDelta([-2.5510, 3.27552, 3.27552], $eig->getRealEigenvalues()->toArray(), 0.1);
        self::assertEqualsWithDelta([0.0, 4.7940, -4.7940], $eig->getImagEigenvalues()->toArray(), 0.1);
    }
}
