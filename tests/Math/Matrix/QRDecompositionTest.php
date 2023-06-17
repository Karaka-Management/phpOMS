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
use phpOMS\Math\Matrix\QRDecomposition;
use phpOMS\Math\Matrix\Vector;

/**
 * @testdox phpOMS\tests\Math\Matrix\QRDecompositionTest: QR decomposition
 *
 * @internal
 */
final class QRDecompositionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A matrix can be checked if it has a full rank
     * @covers phpOMS\Math\Matrix\QRDecomposition
     * @group framework
     */
    public function testRank() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [12, -51, 4],
            [6, 167, -68],
            [-4, 24, -41],
        ]);

        $QR = new QRDecomposition($A);

        self::assertTrue($QR->isFullRank());
    }

    /**
     * @testdox The Q matrix of the decomposition can be calculated
     * @covers phpOMS\Math\Matrix\QRDecomposition
     * @group framework
     */
    public function testQ() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [12, -51, 4],
            [6, 167, -68],
            [-4, 24, -41],
        ]);

        $QR = new QRDecomposition($A);

        self::assertEqualsWithDelta([
            [-6 / 7, 69 / 175, -58 / 175],
            [-3 / 7, -158 / 175, -6 / 175],
            [2 / 7, -6 / 35, -33 / 35],
        ], $QR->getQ()->toArray(), 0.2);
    }

    /**
     * @testdox The R matrix of the decomposition can be calculated
     * @covers phpOMS\Math\Matrix\QRDecomposition
     * @group framework
     */
    public function testR() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [12, -51, 4],
            [6, 167, -68],
            [-4, 24, -41],
        ]);

        $QR = new QRDecomposition($A);

        self::assertEqualsWithDelta([
            [-14, -21, 14],
            [0, -175, 70],
            [0, 0, 35],
        ], $QR->getR()->toArray(), 0.2);
    }

    /**
     * @testdox The decomposition can be created and the original matrix can be computed
     * @covers phpOMS\Math\Matrix\QRDecomposition
     * @group framework
     */
    public function testComposition() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [12, -51, 4],
            [6, 167, -68],
            [-4, 24, -41],
        ]);

        $QR = new QRDecomposition($A);

        self::assertEqualsWithDelta(
            $A->toArray(),
            $QR->getQ()
                ->mult($QR->getR())
                ->toArray(),
            0.2
        );
    }

    /**
     * @testdox The equation Ax = b can be solved
     * @covers phpOMS\Math\Matrix\QRDecomposition
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

        $QR = new QRDecomposition($A);

        $vec = new Vector();
        $vec->setMatrix([[40], [49], [28]]);
        self::assertEqualsWithDelta([[1], [2], [3]], $QR->solve($vec)->toArray(), 0.2);
    }
}
