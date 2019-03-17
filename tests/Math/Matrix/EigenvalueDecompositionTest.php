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

use phpOMS\Math\Matrix\EigenvalueDecomposition;
use phpOMS\Math\Matrix\Matrix;

class EigenvalueDecompositionTest extends \PHPUnit\Framework\TestCase
{
    public function testSymmetricMatrix() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [3, 1, 1],
            [1, 2, 2],
            [1, 2, 2],
        ]);

        $eig = new EigenvalueDecomposition($A);

        self::assertTrue($eig->isSymmetric());
        self::assertEqualsWithDelta([0, 2, 5], $eig->getRealEigenvalues()->toArray(), 0.2);

        self::assertEquals([
            [0, 2/sqrt(6), 1/sqrt(3)],
            [1/sqrt(2), -1/sqrt(6), 1/sqrt(3)],
            [-1/sqrt(2), -1/sqrt(6), 1/sqrt(3)],
        ], $eig->getV()->toArray(), '', 0.2);

        self::assertEquals([
            [0, 0, 0],
            [0, 2, 0],
            [0, 0, 5],
        ], $eig->getD()->toArray(), '', 0.2);
    }

    public function testNonSymmetricMatrix() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [-2, -4, 2],
            [-2, 1, 2],
            [4, 2, 5],
        ]);

        $eig = new EigenvalueDecomposition($A);

        self::assertFalse($eig->isSymmetric());
        self::assertEqualsWithDelta([-5, 3, 6], $eig->getRealEigenvalues()->toArray(), 0.2);

        self::assertEquals([
            [-sqrt(2/3), sqrt(2/7), -1/sqrt(293)],
            [-1/sqrt(6), -3/sqrt(14), -6/sqrt(293)],
            [1/sqrt(6), -1/sqrt(14), -16/sqrt(293)],
        ], $eig->getV()->toArray(), '', 0.2);

        self::assertEquals([
            [-5, 0, 0],
            [0, 3, 0],
            [0, 0, 6],
        ], $eig->getD()->toArray(), '', 0.2);
    }

    public function testCompositeSymmetric() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [3, 1, 1],
            [1, 2, 2],
            [1, 2, 2],
        ]);

        $eig = new EigenvalueDecomposition($A);

        self::assertEquals(
            $A->toArray(),
            $eig->getV()
                ->mult($eig->getD())
                ->mult($eig->getV()->transpose())
                ->toArray()
        , '', 0.2);
    }

    public function testCompositeNonSymmetric() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [-2, -4, 2],
            [-2, 1, 2],
            [4, 2, 5],
        ]);

        $eig = new EigenvalueDecomposition($A);

        self::assertEquals(
            $A->toArray(),
            $eig->getV()
                ->mult($eig->getD())
                ->mult($eig->getV()->inverse())
                ->toArray(),
            '', 0.2
        );
    }
}