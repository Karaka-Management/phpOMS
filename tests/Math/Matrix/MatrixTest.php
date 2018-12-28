<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Math\Matrix;

use phpOMS\Math\Matrix\Matrix;
use phpOMS\Math\Matrix\Vector;

class MatrixTest extends \PHPUnit\Framework\TestCase
{
    protected $A = null;
    protected $B = null;
    protected $C = null;

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

    public function testBase() : void
    {
        self::assertEquals(2, $this->A->getM());
        self::assertEquals(3, $this->A->getN());
        // LU decomposition
    }

    public function testMult() : void
    {
        self::assertEquals([[0, -5], [-6, -7]], $this->C->getMatrix());
        self::assertEquals([[0, -10], [-12, -14]], $this->C->mult(2)->getMatrix());
    }

    public function testAddSub() : void
    {
        $A = new Matrix();
        $A->setMatrix([[1, 2], [3, 4]]);

        self::assertEquals([[1 - 2, 2 - 2], [3 - 2, 4 - 2]], $A->sub(2)->toArray());
        self::assertEquals([[1 + 2, 2 + 2], [3 + 2, 4 + 2]], $A->add(2)->toArray());

        $B = new Matrix();
        $B->setMatrix([[1, 2], [3, 4]]);

        self::assertEquals([[1 - 1, 2 - 2], [3 - 3, 4 - 4]], $A->sub($B)->toArray());
        self::assertEquals([[1 + 1, 2 + 2], [3 + 3, 4 + 4]], $A->add($B)->toArray());
    }

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

    public function testSymmetry() : void
    {
        $B = new Matrix();
        $B->setMatrix([
            [1, 7, 3],
            [7, -2, -5],
            [3, -5, 6],
        ]);

        self::assertTrue($B->isSymmetric());

        $C = new Matrix();
        $C->setMatrix([
            [1, 7, 4],
            [7, -2, -5],
            [3, -5, 6],
        ]);

        self::assertFalse($C->isSymmetric());
    }

    public function testTranspose() : void
    {
        $B = new Matrix();
        $B->setMatrix([
            [6, 1, 1],
            [4, -2, 5],
        ]);

        self::assertEquals([[6, 4], [1, -2], [1, 5],], $B->transpose()->toArray());
    }

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

        self::assertEquals([[1], [2], [3]], $A->solve($vec)->toArray(), '', 0.2);
    }

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

    public function testInverse() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [1, -2, 3],
            [5, 8, -1],
            [2, 1, 1],
        ]);

        self::markTestIncomplete();
        // todo: result column 0 and 1 are swapped. why? still correct?
        /*self::assertEquals([
            [-0.9, -0.5, 2.2],
            [0.7, 0.5, -1.6],
            [1.1, 0.5, -1.8],
        ], $A->inverse()->toArray(), '', 0.2);*/
    }

    public function testReduce() : void
    {
        self::assertEquals([[-6, -7], [0, -5]], $this->C->upperTriangular()->getMatrix());
        //self::assertEquals([], $this->C->lowerTriangular()->getMatrix());
        //self::assertEquals([], $this->C->diagonalize()->getMatrix());
    }

    public function testGetSet() : void
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

    public function testSubMatrix() : void
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
     * @expectedException \phpOMS\Math\Matrix\Exception\InvalidDimensionException
     */
    public function testInvalidSetIndexException() : void
    {
        $id = new Matrix();
        $id->setMatrix([
            [1, 0],
            [0, 1],
        ]);
        $id->set(99, 99, 99);
    }

    /**
     * @expectedException \phpOMS\Math\Matrix\Exception\InvalidDimensionException
     */
    public function testInvalidGetIndexException() : void
    {
        $id = new Matrix();
        $id->setMatrix([
            [1, 0],
            [0, 1],
        ]);
        $id->get(99, 99);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidSub() : void
    {
        $id = new Matrix();
        $id->setMatrix([
            [1, 0],
            [0, 1],
        ]);

        $id->sub(true);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidAdd() : void
    {
        $id = new Matrix();
        $id->setMatrix([
            [1, 0],
            [0, 1],
        ]);

        $id->add(true);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidMult() : void
    {
        $id = new Matrix();
        $id->setMatrix([
            [1, 0],
            [0, 1],
        ]);

        $id->mult(true);
    }

    /**
     * @expectedException \phpOMS\Math\Matrix\Exception\InvalidDimensionException
     */
    public function testInvalidDimensionAdd() : void
    {
        $A = new Matrix();
        $A->setMatrix([[1, 2], [3, 4]]);

        $B = new Matrix();
        $B->setMatrix([[1, 2, 1], [3, 4, 1], [5, 6, 1]]);

        $A->add($B);
    }

    /**
     * @expectedException \phpOMS\Math\Matrix\Exception\InvalidDimensionException
     */
    public function testInvalidDimensionSub() : void
    {
        $A = new Matrix();
        $A->setMatrix([[1, 2], [3, 4]]);

        $B = new Matrix();
        $B->setMatrix([[1, 2, 1], [3, 4, 1], [5, 6, 1]]);

        $A->sub($B);
    }
}
