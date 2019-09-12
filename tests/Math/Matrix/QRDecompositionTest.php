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

use phpOMS\Math\Matrix\Matrix;
use phpOMS\Math\Matrix\QRDecomposition;
use phpOMS\Math\Matrix\Vector;

/**
 * @internal
 */
class QRDecompositionTest extends \PHPUnit\Framework\TestCase
{
    public function testDecomposition() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [12, -51, 4],
            [6, 167, -68],
            [-4, 24, -41],
        ]);

        $QR = new QRDecomposition($A);

        self::assertTrue($QR->isFullRank());

        self::assertEqualsWithDelta([
            [-6 / 7, 69 / 175, -58 / 175],
            [-3 / 7, -158 / 175, -6 / 175],
            [2 / 7, -6 / 35, -33 / 35],
        ], $QR->getQ()->toArray(), 0.2);

        self::assertEqualsWithDelta([
            [-14, -21, 14],
            [0, -175, 70],
            [0, 0, 35],
        ], $QR->getR()->toArray(), 0.2);
    }

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
