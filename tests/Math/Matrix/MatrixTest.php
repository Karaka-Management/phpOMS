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

use phpOMS\Math\Matrix\Matrix;
use phpOMS\Math\Matrix\Vector;

/**
 * @testdox phpOMS\tests\Math\Matrix\MatrixTest: Matrix operations
 *
 * @internal
 */
final class MatrixTest extends \PHPUnit\Framework\TestCase
{
    protected $A = null;

    protected $B = null;

    protected $C = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->A = new Matrix(2, 3);
        $this->A->setMatrix([
            [1, 0, -2],
            [0, 3, -1],
        ]);

        $this->B = new Matrix(3, 2);
        $this->B->setMatrix([
            [0, 3],
            [-2, -1],
            [0, 4],
        ]);

        $this->C = $this->A->mult($this->B);
    }

    /**
     * @testdox A matrix can return its dimension
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testBase() : void
    {
        self::assertEquals(2, $this->A->getM());
        self::assertEquals(3, $this->A->getN());
    }

    /**
     * @testdox A matrix can be right-hand multiplied with a matrix
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testMultMatrix() : void
    {
        self::assertEquals([[0, -5], [-6, -7]], $this->C->toArray());
    }

    /**
     * @testdox A matrix can be right-hand multiplied with a scalar
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testMultMatrixScalar() : void
    {
        self::assertEquals([[0, -10], [-12, -14]], $this->C->mult(2)->toArray());
    }

    /**
     * @testdox A scalar can be added to every matrix element
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testAddScalar() : void
    {
        $A = new Matrix();
        $A->setMatrix([[1, 2], [3, 4]]);

        self::assertEquals([[1 + 2, 2 + 2], [3 + 2, 4 + 2]], $A->add(2)->toArray());
    }

    /**
     * @testdox A scalar can be subtracted from every matrix element
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testSubScalar() : void
    {
        $A = new Matrix();
        $A->setMatrix([[1, 2], [3, 4]]);

        self::assertEquals([[1 - 2, 2 - 2], [3 - 2, 4 - 2]], $A->sub(2)->toArray());
    }

    /**
     * @testdox Two matrices can be added to each other
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testAddMatrix() : void
    {
        $A = new Matrix();
        $A->setMatrix([[1, 2], [3, 4]]);

        $B = new Matrix();
        $B->setMatrix([[1, 2], [3, 4]]);

        self::assertEquals([[1 + 1, 2 + 2], [3 + 3, 4 + 4]], $A->add($B)->toArray());
    }

    /**
     * @testdox Two matrices can be subtracted from each other
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testSubMatrix() : void
    {
        $A = new Matrix();
        $A->setMatrix([[1, 2], [3, 4]]);

        $B = new Matrix();
        $B->setMatrix([[1, 2], [3, 4]]);

        self::assertEquals([[1 - 1, 2 - 2], [3 - 3, 4 - 4]], $A->sub($B)->toArray());
    }

    /**
     * @testdox The determinant of a matrix can be calculated
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testDet() : void
    {
        $B = new Matrix();
        $B->setMatrix([
            [6, 1, 1],
            [4, -2, 5],
            [2, 8, 7],
        ]);

        self::assertEquals(-306, $B->det());
    }

    /**
     * @testdox A symmetric matrix can be validated for symmetry
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testSymmetry() : void
    {
        $B = new Matrix();
        $B->setMatrix([
            [1, 7, 3],
            [7, -2, -5],
            [3, -5, 6],
        ]);

        self::assertTrue($B->isSymmetric());
    }

    /**
     * @testdox A none-symmetric matrix cannot be validated for symmetry
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testInvalidSymmetry() : void
    {
        $C = new Matrix();
        $C->setMatrix([
            [1, 7, 4],
            [7, -2, -5],
            [3, -5, 6],
        ]);

        self::assertFalse($C->isSymmetric());
    }

    /**
     * @testdox A matrix can be transposed
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testTranspose() : void
    {
        $B = new Matrix();
        $B->setMatrix([
            [6, 1, 1],
            [4, -2, 5],
        ]);

        self::assertEquals([[6, 4], [1, -2], [1, 5],], $B->transpose()->toArray());
    }

    /**
     * @testdox A matrix equation Ax = b can be solved for x
     * @covers phpOMS\Math\Matrix\Matrix
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

        $vec = new Vector();
        $vec->setMatrix([[40], [49], [28]]);

        self::assertEqualsWithDelta([[1], [2], [3]], $A->solve($vec)->toArray(), 0.2);
    }

    /**
     * @testdox The rank of a matrix can be calculated
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testRank() : void
    {
        $B = new Matrix();
        $B->setMatrix([
            [0, 1, 2],
            [1, 2, 1],
            [2, 7, 8],
        ]);

        self::assertEquals(2, $B->rank());

        $B->setMatrix([
            [1, 0, 2],
            [2, 1, 0],
            [3, 2, 1],
        ]);
        self::assertEquals(3, $B->rank());

        $B->setMatrix([
            [1, 0, 2],
            [2, 1, 0],
        ]);
        self::assertEquals(2, $B->rank());

        $B->setMatrix([
            [1, 2],
            [0, 1],
            [2, 0],
        ]);
        self::assertEquals(2, $B->rank());
    }

    /**
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testInverse() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [1, -2, 3],
            [5, 8, -1],
            [2, 1, 1],
        ]);

        self::assertEqualsWithDelta([
            [-0.9, -0.5, 2.2],
            [0.7, 0.5, -1.6],
            [1.1, 0.5, -1.8],
        ], $A->inverse()->toArray(), 0.2);
    }

    /**
     * @testdox The upper triangular matrix can be calculated
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testUpperTriangular() : void
    {
        self::assertEquals([[-6, -7], [0, -5]], $this->C->upperTriangular()->toArray());
    }

    /**
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testLowerTriangular() : void
    {
        self::markTestIncomplete();
        //self::assertEquals([], $this->C->lowerTriangular()->toArray());
        //self::assertEquals([], $this->C->diagonalize()->toArray());
    }

    /**
     * @testdox The matrix elements can be set and returned
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testMatrixInputOutput() : void
    {
        $id = new Matrix();
        $id->setMatrix([
            [1, 0, 0, 0, 0],
            [0, 1, 0, 0, 0],
            [0, 0, 1, 0, 0],
            [0, 0, 0, 1, 0],
            [0, 0, 0, 0, 1],
        ]);

        self::assertEquals(1, $id->get(1, 1));
        self::assertEquals(0, $id->get(1, 2));

        $id->set(1, 2, 4);
        self::assertEquals(4, $id->get(1, 2));
        self::assertEquals(
            [
                [1, 0, 0, 0, 0],
                [0, 1, 4, 0, 0],
                [0, 0, 1, 0, 0],
                [0, 0, 0, 1, 0],
                [0, 0, 0, 0, 1],
            ],
            $id->toArray()
        );
    }

    /**
     * @testdox A matrix can be accessed like a 1-dimensional array
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testArrayAccess() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [0, 1, 2, 3],
            [4, 5, 6, 7],
            [8, 9, 10, 11],
            [12, 13, 14, 15],
        ]);

        foreach ($A as $key => $value) {
            self::assertEquals($key, $value);
        }

        self::assertEquals(5, $A[5]);

        $A[5] = 6;
        self::assertEquals(6, $A[5]);

        self::assertTrue(isset($A[6]));
        self::assertFalse(isset($A[17]));

        unset($A[6]);
        self::assertFalse(isset($A[6]));
    }

    /**
     * @testdox Sub matrices can be extracted from a matrix
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testMatrixExtract() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [0, 1, 2, 3],
            [4, 5, 6, 7],
            [8, 9, 10, 11],
            [12, 13, 14, 15],
        ]);

        self::assertEquals(
            [[1, 2], [5, 6], [9, 10]],
            $A->getSubMatrix(0, 2, 1, 2)->toArray()
        );

        self::assertEquals(
            [[1, 2], [5, 6], [9, 10]],
            $A->getSubMatrixByColumnsRows([0, 1, 2], [1, 2])->toArray()
        );

        self::assertEquals(
            [[1, 2], [5, 6], [9, 10]],
            $A->getSubMatrixByColumns(0, 2, [1, 2])->toArray()
        );

        self::assertEquals(
            [[1, 2], [5, 6], [9, 10]],
            $A->getSubMatrixByRows([0, 1, 2], 1, 2)->toArray()
        );
    }

    /**
     * @testdox Setting a matrix element outside of the dimensions throws a InvalidDimensionException
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testInvalidSetIndexException() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        $id = new Matrix();
        $id->setMatrix([
            [1, 0],
            [0, 1],
        ]);
        $id->set(99, 99, 99);
    }

    /**
     * @testdox Returning a matrix element outside of the dimensions throws a InvalidDimensionException
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testInvalidGetIndexException() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        $id = new Matrix();
        $id->setMatrix([
            [1, 0],
            [0, 1],
        ]);
        $id->get(99, 99);
    }

    /**
     * @testdox Adding a matrix with a different dimension to a matrix throws a InvalidDimensionException
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testInvalidDimensionAdd() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        $A = new Matrix();
        $A->setMatrix([[1, 2], [3, 4]]);

        $B = new Matrix();
        $B->setMatrix([[1, 2, 1], [3, 4, 1], [5, 6, 1]]);

        $A->add($B);
    }

    /**
     * @testdox Subtracting a matrix from a different dimension to a matrix throws a InvalidDimensionException
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testInvalidDimensionSub() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        $A = new Matrix();
        $A->setMatrix([[1, 2], [3, 4]]);

        $B = new Matrix();
        $B->setMatrix([[1, 2, 1], [3, 4, 1], [5, 6, 1]]);

        $A->sub($B);
    }

    /**
     * @testdox Multiplying a matrix with a different n x m dimension to a matrix throws a InvalidDimensionException
     * @covers phpOMS\Math\Matrix\Matrix
     * @group framework
     */
    public function testInvalidDimensionMult() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        $A = new Matrix();
        $A->setMatrix([[1, 2], [3, 4]]);

        $B = new Matrix();
        $B->setMatrix([[1, 2, 1], [3, 4, 1], [5, 6, 1]]);

        $A->mult($B);
    }

    public function testSumAll() : void
    {
        $m = Matrix::fromArray([
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9],
        ]);

        self::assertEquals(
            45,
            $m->sum(-1)
        );
    }

    public function testSumColumns() : void
    {
        $m = Matrix::fromArray([
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9],
        ]);

        self::assertEquals(
            [12, 15, 18],
            $m->sum(0)->toVectorArray()
        );
    }

    public function testSumRows() : void
    {
        $m = Matrix::fromArray([
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9],
        ]);

        self::assertEquals(
            [6, 15, 24],
            $m->sum(1)->toVectorArray()
        );
    }

    public function testDiaglonal() : void
    {
        $m = Matrix::fromArray([
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9],
        ]);

        self::assertFalse($m->isDiagonal());

        $m = Matrix::fromArray([
            [1, 0, 0],
            [0, 5, 0],
            [0, 0, -8],
        ]);

        self::assertTrue($m->isDiagonal());
    }

    public function testPow() : void
    {
        $m = Matrix::fromArray([
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9],
        ]);

        self::assertEquals(
            [
                [30, 36, 42],
                [66, 81, 96],
                [102, 126, 150],
            ],
            $m->pow(2)->toArray()
        );

        $m = Matrix::fromArray([
            [1.5, 2.5, 3.5],
            [4.5, 5.5, 6.5],
            [7.5, 8.5, 9.5],
        ]);

        self::assertEqualsWithDelta(
            [
                [39.75, 47.25, 54.75],
                [80.25, 96.75, 113.25],
                [120.75, 146.25, 171.75],
            ],
            $m->pow(2)->toArray(),
            0.1
        );

        $m = Matrix::fromArray([
            [1, 1, 1],
            [1, 2, 3],
            [1, 3, 6],
        ]);

        self::assertEqualsWithDelta(
            [
                [0.8901, 0.5882, 0.3684],
                [0.5882, 1.2035, 1.3799],
                [0.3684, 1.3799, 3.1167],
            ],
            $m->pow(2 / 3)->toArray(),
            0.01
        );
    }

    public function testExp() : void
    {
        $m = Matrix::fromArray([
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9],
        ]);

        self::assertEqualsWithDelta(
            [
                [1118906.6994131860386,   1374815.062935806540981, 1630724.426458427043361],
                [2533881.041898971697907, 3113415.03138055427637,  3692947.020862136854833],
                [3948856.384384757357213, 4852012.999825302011759, 5755170.615265846666304],
            ],
            $m->exp()->toArray(),
            1.0
        );
    }
}
