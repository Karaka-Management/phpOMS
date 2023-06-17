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

use phpOMS\Math\Matrix\EigenvalueDecomposition;
use phpOMS\Math\Matrix\Matrix;

/**
 * @testdox phpOMS\tests\Math\Matrix\EigenvalueDecompositionTest: Eigenvalue decomposition
 *
 * @internal
 */
final class EigenvalueDecompositionTest extends \PHPUnit\Framework\TestCase
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
    /*
    Testing for this makes little sense, since this can change depending on the algorithm, precision etc.
    It's much more important to check the identity A = VDV' which is done in the test "testCompositeNonSymmetric"
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
    */

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

    /**
     * @covers phpOMS\Math\Matrix\EigenvalueDecomposition
     * @group framework
     */
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

    /**
     * @covers phpOMS\Math\Matrix\EigenvalueDecomposition
     * @group framework
     */
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

    /**
     * @covers phpOMS\Math\Matrix\EigenvalueDecomposition
     * @group framework
     */
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

    /**
     * @covers phpOMS\Math\Matrix\EigenvalueDecomposition
     * @group framework
     */
    public function testComplexDivision3() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [9, 4, 5, 1],
            [-1, 15, -2, 13],
            [-14, 7, 15, -13],
            [13, -16, -2, 19],
        ]);

        $eig = new EigenvalueDecomposition($A);

        self::assertEqualsWithDelta([
            [17.7766, 14.8641, 0.0, 0.0],
            [-14.8641, 17.7766, 0.0, 0.0],
            [0.0, 0.0, 11.22336, 5.6595],
            [0.0, 0.0, -5.6595, 11.22336],
        ], $eig->getD()->toArray(), 0.1);

        self::assertEqualsWithDelta([17.7766, 17.7766, 11.2233, 11.2233], $eig->getRealEigenvalues()->toArray(), 0.1);
        self::assertEqualsWithDelta([14.8641, -14.8641, 5.6595, -5.6595], $eig->getImagEigenvalues()->toArray(), 0.1);
    }

    /**
     * @covers phpOMS\Math\Matrix\EigenvalueDecomposition
     * @group framework
     */
    public function testComplexDivision4() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [5, 14, 5, -6],
            [13, 12, -4, -3],
            [13, 10, 8, 17],
            [5, -6, 3, 16],
        ]);

        $eig = new EigenvalueDecomposition($A);

        self::assertEqualsWithDelta([
            [22.6519, 3.96406, 0.0, 0.0],
            [-3.96406, 22.6519, 0.0, 0.0],
            [0.0, 0.0, -2.1519, 3.39498],
            [0.0, 0.0, -3.39498, -2.1519],
        ], $eig->getD()->toArray(), 0.1);

        self::assertEqualsWithDelta([22.6519, 22.6519, -2.1519, -2.1519], $eig->getRealEigenvalues()->toArray(), 0.1);
        self::assertEqualsWithDelta([3.96406, -3.96406, 3.39498, -3.39498], $eig->getImagEigenvalues()->toArray(), 0.1);
    }
}
/*
        Test case finder
        $c = 0;
        try {
            do {
                $array = [];
                for ($i = 0; $i < 4; ++$i) {
                    $array[] = [];
                    for ($j = 0; $j < 4; ++$j) {
                        $div = \mt_rand(-20, 20);

                        $array[$i][] = \mt_rand(-20, 20);
                    }
                }

                $A = new Matrix();
                $A->setMatrix($array);

                $eig = new EigenvalueDecomposition($A);
                ++$c;
            } while (true);
        } catch (\Throwable $t) {
            var_dump($c);
            var_dump($array);
        }
*/
